import pandas as pd
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.compose import ColumnTransformer
from sklearn.preprocessing import StandardScaler, OneHotEncoder
from sklearn.pipeline import Pipeline
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report
import pickle
import pyodbc



cnxn_db_local = pyodbc.connect(
    "DRIVER={ODBC Driver 17 for SQL Server};"
    "SERVER=Dian;"
    "DATABASE=system;"
    "Trusted_Connection=yes;"
    "TrustServerCertificate=yes;"   
)


df_CRTN =  pd.read_sql("SELECT company_code, branch_code, subasset_code,subasset_name, asset_group_lvl1, asset_code, revision_number, document_line, asset_condition,	asset_life, location, acquisition_value, depreciation_value, book_value, economy_life, acquisition_date,status FROM CRTN", cnxn_db_local)


df_TRIN =  pd.read_sql("SELECT company_code, branch_code, subasset_code,subasset_name, asset_group_lvl1, asset_code, revision_number, document_line, asset_condition,	asset_life, location, acquisition_value, depreciation_value, book_value, economy_life, acquisition_date,status FROM TRIN", cnxn_db_local)


# def preprocess_data(data):
#     # Print columns to check for 'acquisition_date'
#     print("Columns before preprocessing:", data.columns)
    
#     # Convert acquisition_date to datetime
#     if 'acquisition_date' in data.columns:
#         data['acquisition_date'] = pd.to_datetime(data['acquisition_date'])
#         data['acquisition_year'] = data['acquisition_date'].dt.year
#         data['acquisition_month'] = data['acquisition_date'].dt.month
#         data['acquisition_day'] = data['acquisition_date'].dt.day
#     else:
#         print("Warning: 'acquisition_date' column is missing.")
#     return data

# Function to add non-duplicate rows from CRTN to RSLT
def add_non_duplicate_rows(existing_data, correction_data):
    # Merge the existing data with the correction data on the primary key to identify non-duplicate rows
    non_duplicate_data = correction_data.merge(
        existing_data[['company_code', 'branch_code', 'subasset_code', 'revision_number', 'document_line']],
        on=['company_code', 'branch_code', 'subasset_code', 'revision_number', 'document_line'],
        how='left',
        indicator=True
    )
    
    # Select rows that do not have a match in the existing data
    non_duplicate_data = non_duplicate_data[non_duplicate_data['_merge'] == 'left_only'].drop(columns=['_merge'])
    
    # Append non-duplicate rows to the existing data
    combined_data = pd.concat([existing_data, non_duplicate_data], ignore_index=True)
    
    return combined_data

# Function to update the existing data with new labels
def update_existing_data(existing_data, correction_data):
    # Merge the existing data with the correction data on the primary key
    updated_data = existing_data.merge(
        correction_data[['company_code', 'branch_code', 'subasset_code', 'revision_number', 'document_line', 'status']],
        on=['company_code', 'branch_code', 'subasset_code', 'revision_number', 'document_line'],
        how='left',
        suffixes=('', '_new')
    )
    
    # Update the status and cluster fields with the new values if they exist
    updated_data['status'] = updated_data['status_new'].combine_first(updated_data['status'])
    
    
    # Drop the temporary columns
    updated_data = updated_data.drop(columns=['status_new'])
    
    return updated_data

# Function to convert the acquisition date parts back to YYYYMMDD format
# def convert_date_to_yyyymmdd(data):
#     data['acquisition_date'] = (
#         data['acquisition_year'].astype(str) + 
#         data['acquisition_month'].astype(str).str.zfill(2) + 
#         data['acquisition_day'].astype(str).str.zfill(2)
#     ).astype(int)
#     return data.drop(columns=['acquisition_year', 'acquisition_month', 'acquisition_day'])

# Function to save the updated data back to the SQL server
def save_updated_data(data):
    # Convert date parts back to YYYYMMDD format
    # data = convert_date_to_yyyymmdd(data)

    
    
    with cnxn_db_local.cursor() as cursor:
            # Delete all rows in the table
            cursor.execute("DELETE FROM dbo.TRIN")
            cnxn_db_local.commit()  # Commit the deletion 
    
    # Insert data into the table
    with cnxn_db_local.cursor() as cursor:
        sql_query = """
        INSERT INTO dbo.TRIN (
            company_code, branch_code, subasset_code, subasset_name, asset_group_lvl1, asset_code, revision_number, document_line, economy_life, asset_life, asset_condition, acquisition_date,
            acquisition_value, depreciation_value, book_value, location, status
        ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
        """
        
        # Convert DataFrame to list of tuples for insertion
        data_tuples = data[['company_code', 'branch_code', 'subasset_code', 'subasset_name', 'asset_group_lvl1','asset_code', 'revision_number', 'document_line', 'economy_life', 'asset_life', 'asset_condition',
                        'acquisition_date', 'acquisition_value', 'depreciation_value', 
                        'book_value', 'location',  'status']].values.tolist()
        
        # # Execute the insert query for each row
        # cursor.executemany(sql_query, data_tuples)

        for row in data_tuples:
            try:
                cursor.execute(sql_query, row)
            except Exception as e:
                print(f"Error inserting row: {row}")
                print(e)

        cursor.execute("DELETE FROM CRTN")
        
        # Commit the transaction
        cnxn_db_local.commit()

# Load the existing model
pickle_file = 'asset_classification_model.pkl'
with open(pickle_file, 'rb') as file:
    pipeline = pickle.load(file)

# Check if there are 100 or more new data points
if len(df_CRTN) >= 100:


    # Placeholder functions
    # def preprocess_data(df):
    #     # Convert acquisition_date to datetime and extract year, month, day
    #     df['acquisition_date'] = pd.to_datetime(df['acquisition_date'])
    #     df['acquisition_year'] = df['acquisition_date'].dt.year
    #     df['acquisition_month'] = df['acquisition_date'].dt.month
    #     df['acquisition_day'] = df['acquisition_date'].dt.day
    #     df = df.drop('acquisition_date', axis=1)
    #     return df

    # def update_existing_data(df_TRIN, df_CRTN):
    #     # Placeholder for actual update code
    #     return df_TRIN

    # def add_non_duplicate_rows(df_TRIN, df_CRTN):
    #     combined = pd.concat([df_TRIN, df_CRTN]).drop_duplicates().reset_index(drop=True)
    #     return combined

    # def save_updated_data(combined_data):
    #     # Placeholder for actual save code
    #     pass

    # Preprocess the correction data and existing training data
    # df_CRTN = preprocess_data(df_CRTN)
    # df_TRIN = preprocess_data(df_TRIN)

    # Separate rows where asset_group_lvl1 is "TN" and set their status to "Good"
    tn_df_CRTN = df_CRTN[df_CRTN['asset_group_lvl1'] == 'TN'].copy()
    tn_df_CRTN['status'] = 'Keep'

    tn_df_TRIN = df_TRIN[df_TRIN['asset_group_lvl1'] == 'TN'].copy()
    tn_df_TRIN['status'] = 'Keep'

    # Exclude these rows from the correction and training data
    df_CRTN = df_CRTN[df_CRTN['asset_group_lvl1'] != 'TN']
    df_TRIN = df_TRIN[df_TRIN['asset_group_lvl1'] != 'TN']

    # Combine TN data
    tn_combined_data = add_non_duplicate_rows(tn_df_TRIN, tn_df_CRTN)

    # Update the existing data with new labels (excluding TN data)
    df_TRIN = update_existing_data(df_TRIN, df_CRTN)

    # Combine all data, ensuring no duplicates (excluding TN data)
    combined_data = add_non_duplicate_rows(df_TRIN, df_CRTN)

    # Limit the combined data to the most recent 100,000 rows
    if len(combined_data) > 100000:
        combined_data = combined_data.iloc[-100000:]

    # Separate features and target for training data (excluding TN data)
    features = ['economy_life', 'asset_life', 'asset_condition', 'acquisition_value', 'depreciation_value', 'book_value']
    X = combined_data[features]
    y = combined_data['status']

    # Split data into training and test sets
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # Identify categorical and numerical columns
    categorical_features = ['asset_condition']
    numerical_features = ['asset_life', 'acquisition_value', 'depreciation_value', 'book_value', 'economy_life']

    # Preprocess data using ColumnTransformer
    preprocessor = ColumnTransformer(
        transformers=[
            ('num', StandardScaler(), numerical_features),
            ('cat', OneHotEncoder(), categorical_features)
        ])

    # Create a pipeline with preprocessing and model training
    pipeline = Pipeline(steps=[
        ('preprocessor', preprocessor),
        ('classifier', RandomForestClassifier(random_state=42))
    ])

    # Train the model
    pipeline.fit(X_train, y_train)

    # Perform cross-validation on the training data
    cv_scores = cross_val_score(pipeline, X_train, y_train, cv=5)  # 5-fold cross-validation

    # Print cross-validation scores
    print("Cross-validation scores:", cv_scores)
    print("Mean cross-validation score:", cv_scores.mean())

    # Predict on the test set
    y_pred = pipeline.predict(X_test)

    # Evaluate the model on the test set
    print("Accuracy on test set:", accuracy_score(y_test, y_pred))
    print("Classification Report on test set:\n", classification_report(y_test, y_pred))

    # Save the updated model
    pickle_file = 'asset_classification_model_new.pkl'
    with open(pickle_file, 'wb') as file:
        pickle.dump(pipeline, file)

    # Combine the classified data with the TN data
    final_combined_data = add_non_duplicate_rows(combined_data, tn_combined_data)

    # Save the combined data back to the SQL server
    save_updated_data(final_combined_data)

    print("Updated Model Successfully.")
else:
    print("Not enough data for retrain")