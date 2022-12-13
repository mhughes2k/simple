var marked_row = new Array;
/**
 * marks all rows and selects its first checkbox inside the given element
 * the given element is usaly a table or a div containing the table or tables
 *
 * @param    container    DOM element
 */
function markAllRows( container_id ) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;
    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];
        if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            if ( checkbox.disabled == false ) {
                checkbox.checked = true;
                if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
                    rows[i].className += ' marked';
                    marked_row[unique_id] = true;
                }
            }
        }
    }

    return true;
}

/**
 * marks all rows and selects its first checkbox inside the given element
 * the given element is usaly a table or a div containing the table or tables
 *
 * @param    container    DOM element
 */
function unMarkAllRows( container_id ) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];

        if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            checkbox.checked = false;
            rows[i].className = rows[i].className.replace(' marked', '');
            marked_row[unique_id] = false;
        }
    }

    return true;
}

/**
* directs the user to the simulation management page for the selected simulation
* @param 	menu 	dropdown id
*/
function ManageSim(menu) {
	var simId = GetDropdown(menu);
	window.location = "index.php?option=projectAdmin&cmd=viewProject&projectId="+simId;	
}

/**
* directs the user to the simulation page for the selected simulation
* @param 	menu	dropdown id
*/
function EnterSim(menu) {
	var simId = GetDropdown(menu);
	window.location = "index.php?option=tl&cmd=select&redirect=office&projectid="+simId;
}

/**
* gets the current value of the specified dropdown menu
* @param	menu	dropdown id
*/
function GetDropdown(menu) {
	var dropdown = document.getElementById(menu);
	var index  = dropdown.options[dropdown.selectedIndex].value;
	return index;
}

