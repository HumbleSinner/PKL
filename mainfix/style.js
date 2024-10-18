
const dropdowns = document.querySelectorAll('.dropdown');


dropdowns.forEach(dropdown => {
   
    const select = dropdown.querySelector('.select');
    const caret = dropdown.querySelector('.caret');
    const menu = dropdown.querySelector('.menu');
    const options = dropdown.querySelectorAll('.menu li');
    const selected = dropdown.querySelector('.selected');

   


    select.addEventListener('click', () => {
       
        select.classList.toggle('select-clicked');
       
        caret.classList.toggle('caret-rotate');
         
        menu.classList.toggle('menu-open');
    });

    
    options.forEach(option => {
        
        option.addEventListener('click', () => {
            
            selected.innerText = option.innerText;
            
            select.classList.remove('select-clicked');
            
            caret.classList.remove('caret-rotate');
            
            menu.classList.remove('menu-open');
           
            options.forEach(opt => {
                opt.classList.remove('active');
            });
           
            option.classList.add('active');
        });
    });
});

$(document).ready(function() {
    $('#table-dropdown li').on('click', function() {
        var tableName = $(this).text();
        $.ajax({
            url: 'connect_sql.php',
            method: 'GET',
            data: { action: 'get_columns', table: tableName },
            success: function(response) {
                var columns = JSON.parse(response);
                $('#nama_aset-dropdown').empty();
                $.each(columns, function(index, column) {
                    $('#nama_aset-dropdown').append('<li>' + column + '</li>');
                });
                
            }
        });
    });
});
