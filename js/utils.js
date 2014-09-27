/**
 * Returns default value if undefined.
 * 
 * @param mixed value
 * @param mixed defaultValue
 * 
 * @return mixed Value if defined or defaultvalue if undefined.
 */
 function defaultTo(value, defaultValue) {
	 return (typeof(value) === 'undefined') ? defaultValue : value;
 }

/*
 * Returns the url as stored in a hidden field of the document
 */
function getUrl() {
    var url = $("#url");
    
    if (url.length == 1)
        return url.val();
    else {
        alert('getUrl: url not specified on html page');
    }
}

function createUrl(route) {
    if (route && route.length > 0) {
        if (!route.indexOf('/') ==0) {
            route = '/' + route;
        }
        return getUrl() + route;
    } else {
        return getUrl();
    }
}

function alertJSON(data) {
    alert(JSON.stringify(data));
}

/*
 * returns the get action that resulted in the displayed gridview.
 */
function getGridGetAction() {
    var keys = $("div.grid-view").find("div.keys");
    var getAction = '';
    switch (keys.length) {
        case 0:
            alert('No gridview keys found');
            getAction = '';
            break;
        case 1:
            getAction = keys.attr('title');
            break;
        default:
            alert('Mutiple gridview keys found');
            getAction = '';
            break;
    }
    
    return getAction;
}

function updateGridView() {
    var grid = $(".grid-view");
    
    switch (grid.length) {
        case 0:
            alert('utils.function updateGridView failed: grid not found');
            break;
        case 1:
            grid.yiiGridView.update(grid.attr('id'));
            break;
        default:
            alert('utils.function updateGridView failed: multiple grids found');
            break;
    }
}
