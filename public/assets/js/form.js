/*
 * Deal with form lines.
 * - add lines
 * - TODO remove lines
 */

/*
 * EVENT LISTENERS
 */

let button_add_row = document.getElementById('add_row');
button_add_row.addEventListener("click", (event) => {event.preventDefault();});
button_add_row.addEventListener("click", create_new_line); // éviter le comportement par défaut (?) de rechargement

button_delete_row_event_listener();

/*
 * FUNCTIONS
 */

function button_delete_row_event_listener() {
    let buttons_del_row = Array.from(document.getElementsByClassName('del_row'));
    buttons_del_row.map( button => {
        button.addEventListener("click", (event) => {event.preventDefault();});
        button.addEventListener("click", delete_row);
    });
}

// we may allow the user to remove a line from the form.

function create_new_line() {
    let table_body = document.getElementsByTagName("tbody")[0];
    add_line_to_form( table_body );
    button_delete_row_event_listener();
}

function add_line_to_form(table_body) {
    let form_rows = Array.from(table_body.children)
    let last_line_number = getFormRowNumber(form_rows.at(-1));

    let model_row = form_rows[0];
    table_body.appendChild(
        create_empty_row(
            last_line_number + 1,
            model_row,
        )
    )
}

function getFormRowNumber(form_row) {
    let inputID = form_row.children[2].firstChild.id;
    let form_row_number = inputID.split('_').at(-2);
    return Number(form_row_number);
}

function create_empty_row(line_number, model_row) {

    let new_row = model_row.cloneNode(true);
    let model_number = getFormRowNumber(model_row);
    let new_row_cells = new_row.children;

    new_row_cells[0].innerText = line_number;
    replaceIdNumber(new_row_cells[1].firstChild, model_number, line_number);
    replaceIdNumber(new_row_cells[2].firstChild, model_number, line_number);
    replaceNameNumber(new_row_cells[1].firstChild, model_number, line_number);
    replaceNameNumber(new_row_cells[2].firstChild, model_number, line_number);

    return new_row;
}

function replaceIdNumber(input, model_number, line_number) {
    let id = input.id;
    input.id = id.replace(model_number, line_number)
}

function replaceNameNumber(input, model_number, line_number) {
    let name = input.name;
    input.name = name.replace(model_number, line_number)
}

// TODO
function delete_row(event) {
    console.log(event);
}