from IPython.display import display
import pyodbc
import pickle
import pandas as pd
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.compose import ColumnTransformer
from sklearn.preprocessing import StandardScaler, OneHotEncoder
from sklearn.pipeline import Pipeline
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report
import retrain
import shap




# Connect to the database
cnxn_db_local = pyodbc.connect(
    "DRIVER={ODBC Driver 17 for SQL Server};"
    "SERVER=DIAN;"
    "DATABASE=system;"
    "Trusted_Connection=yes;"
    "TrustServerCertificate=yes;"
)

# Read data from the database
df = pd.read_sql("SELECT company_code, branch_code, subasset_code, subasset_name, asset_group_lvl1, asset_code, revision_number, document_line, asset_condition, asset_life, location, acquisition_value, depreciation_value, book_value, economy_life, acquisition_date FROM RSLT", cnxn_db_local)

# Define the mapping
condition_mapping = {
    "Baik": 1,         # Good
    "Rusak": 2,        # Broken
    "Tidak Diketahui": 3,  # Unknown
    '': 0              # Blank or no data
}

# Reverse mapping
reverse_condition_mapping = {v: k for k, v in condition_mapping.items()}

# Apply the mapping
df['asset_condition'] = df['asset_condition'].map(condition_mapping)

# Function to preprocess data
def preprocess_data(df):
    # Select only the specified six parameters
    df = df[['asset_life', 'asset_condition', 'acquisition_value', 'depreciation_value', 'book_value', 'economy_life']]
    return df

# Load the model from the pickle file
pickle_file = 'asset_classification_model.pkl'
with open(pickle_file, 'rb') as file:
    pipeline = pickle.load(file)

# Separate rows where asset_group_lvl1 is "TN"
tn_rows = df[df['asset_group_lvl1'] == 'TN'].copy()
tn_rows['status'] = 'Keep'
tn_rows['description'] = 'Status otomatis "Keep" untuk grup TN'

# Exclude these rows from the input data for model prediction
df_input = df[df['asset_group_lvl1'] != 'TN'].copy()

# Preprocess the data
df_parameter = preprocess_data(df_input)
df_parameter = df_parameter.apply(pd.to_numeric, errors='coerce')
df_parameter.fillna(0, inplace=True)

# Predict using the pipeline
predictions = pipeline.predict(df_parameter)
df_input['status'] = predictions

# SHAP explanation
explainer = shap.Explainer(pipeline['classifier'], df_parameter)
shap_values = explainer(df_parameter)

# Generate explanations for each row
def generate_explanation(row, shap_values, prediction):
    explanation = f"Aset diklasifikasikan sebagai '{prediction}' karena:"
    try:
        feature_importances = sorted(zip(shap_values.values[row], shap_values.feature_names), key=lambda x: abs(x[0]), reverse=True)
        for importance, feature in feature_importances:
            explanation += f"\n- Nilai dari {feature} adalah {df_input.iloc[row][feature]} yang berkontribusi {'positif' if importance > 0 else 'negatif'} untuk prediksi ini."
    except IndexError as e:
        explanation += f"\nTerjadi kesalahan dalam menghasilkan penjelasan: {str(e)}"
    return explanation

df_input['description'] = [
    generate_explanation(i, shap_values[i], predictions[i]) for i in range(len(df_input))
]

# Revert asset_condition back to original values using reverse mapping
df_input['asset_condition'] = df_input['asset_condition'].map(reverse_condition_mapping)
tn_rows['asset_condition'] = tn_rows['asset_condition'].map(reverse_condition_mapping)

# Combine the rows with 'TN' status and the model predictions
combined_data = pd.concat([df_input, tn_rows], ignore_index=True)

print("Data gabungan akhir:")
with pd.option_context('display.max_rows', 100,
                       'display.max_columns', 100,
                       'display.min_rows', 99,
                       'display.precision', 3):
    print(combined_data)

# Verify acquisition_date is included and convert to int
combined_data['acquisition_date'] = combined_data['acquisition_date'].astype(int, errors='ignore')

print(combined_data.dtypes)

# Insert data into the table
with cnxn_db_local.cursor() as cursor:
    cursor.execute("DELETE FROM RSLT;")

    cursor.execute("""
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'RSLT' AND COLUMN_NAME = 'status';
    """)
    column_exists = cursor.fetchone()

    if not column_exists:
        cursor.execute("ALTER TABLE RSLT ADD status NVARCHAR(20);")


    sql_query = """
    INSERT INTO dbo.RSLT (
        company_code, branch_code, subasset_code, subasset_name, asset_code, asset_group_lvl1, revision_number, document_line, economy_life, asset_life, asset_condition,
        acquisition_value, depreciation_value, book_value, location, status, acquisition_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
    """

    data_tuples = combined_data[['company_code', 'branch_code', 'subasset_code', 'subasset_name', 'asset_code', 'asset_group_lvl1', 'revision_number', 'document_line', 'economy_life', 'asset_life', 'asset_condition',
                                 'acquisition_value', 'depreciation_value', 'book_value', 'location', 'status',  'acquisition_date']].values.tolist()
     

    for row in data_tuples:
        try:
            cursor.execute(sql_query, row)
        except Exception as e:
            print(f"Error inserting row: {row}")
            print(e)




    df_CRTN_COUNT = cursor.execute ("SELECT COUNT (*) FROM CRTN") 

    cnxn_db_local.commit()


#Decision to running the retrain or not, based on the correction inputted before.
if df_CRTN_COUNT >= 100:
    retrain ()
else :
    ("Not Enough Data, Input More Correction!")


df_CRTN = pd.read_sql("SELECT company_code, branch_code, subasset_code, subasset_name, asset_group_lvl1, asset_code, revision_number, document_line, asset_condition, asset_life, location, acquisition_value, depreciation_value, book_value, economy_life, acquisition_date, status FROM CRTN", cnxn_db_local)

import subprocess

php_script = 'label.php'
subprocess.run(['php', php_script])

print("Script executed successfully.")
