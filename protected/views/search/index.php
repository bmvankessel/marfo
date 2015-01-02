<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * Renders the search page.
 * 
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
?>
<div class="row test">
    <div class="col-xs-3">
        <h1>Producten</h1>
    </div>
        <div class="col-xs-3">
<?php
    echo CHtml::beginForm();
    echo CHtml::textField('Search[code]', $selectedCode, array('class'=>'searchInput', 'placeholder'=>'op nummer...'));
    echo CHtml::tag('input', array('id'=>'search-code', 'type'=>'submit', 'class'=>'searchButton', 'value'=>$selectedCode));
    echo CHtml::endForm();
?>
        </div>
        <div class="col-xs-3">
<?php
    echo CHtml::beginForm();
    echo CHtml::textField('Search[omschrijving]', $selectedDescription, array('class'=>'searchInput', 'placeholder'=>'op naam...'));
    echo CHtml::tag('input', array('id'=>'search-code', 'type'=>'submit', 'class'=>'searchButton'));
    echo CHtml::endForm();
?>
        </div>
        <div class="col-xs-3">
<?php
    echo CHtml::openTag('div', array('class'=>'col-xs-12'));
    echo CHtml::beginForm('','post', array('id'=>'send-date'));
    $this->widget('CMaskedTextField', 
        array(
            'name'=>'Search[specificatie_datum]',
            'mask'=>'99-99-99',
            'value' => $selectedDate,
            'completed' => 'function() {window.validation.checkDate(this.val());}',
            'htmlOptions'=>array(
                'class'=>'searchInput',
                'placeholder'=>'nieuw vanaf d.d....',
            )
        )
    );
    //echo CHtml::textField('Search[specificatie_datum]', $selectedDate, array('class'=>'searchInput', 'placeholder'=>'nieuw vanaf d.d....'));
    echo CHtml::tag('input', array('id'=>'search-code', 'type'=>'submit', 'class'=>'searchButton'));
    echo Chtml::endForm();
    echo CHtml::closeTag('div');
    echo CHtml::tag('div',array('id'=>'error-date', 'class'=>'col-xs-12 text-danger hidden'),
        CHtml::tag('span', array('class'=>'glyphicon glyphicon-warning-sign'), '&nbsp;' ) .
        CHtml::tag('span', array('class'=>'message'), '')
    );
?>
        </div>
</div>

<div class="row">
    <div class="col-xs-3 search">
        <?=Render::searchNavigation($model->searchNavigation($selectedMenu));?>
    </div>
    <div class="col-xs-9">

<?php

    $description = '"<h1 class=\"meal\" title=\"Aanmaken PDF\">" . $data->omschrijving . "</h1>" .';
    $description .= '"<h2>" . $data->code . "</h2>" .';
//    $description .= '"<p class=\"hidden\">" . htmlspecialchars($data->ingredientendeclaratie . " ") . "<a class=\"pdf\" onclick=\"createPdf($data->id)\">Meer informatie <span id=\"$data->id\" class=\"fa fa-file-pdf-o fa-lg\"></span></a></p>"';
    $description .= '"<p class=\"hidden\">" . $data->ingredientendeclaratie . "<a class=\"pdf\" onclick=\"createPdf($data->id)\">Meer informatie <span id=\"$data->id\" class=\"fa fa-file-pdf-o fa-lg\"></span></a></p>"';


    $image = '';

    $image = '"<img class=\"meal\" title=\"Aanmaken PDF\" src=\"" . Yii::app()->createUrl("maaltijd-img") . "/" . ((file_exists(Yii::getPathOfAlias("maaltijdimg") . "/" . $data->code . ".jpg")) ? $data->code .".jpg" : "no-image.png") ."\">"';


    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'result-grid',
        'itemsCssClass'=>'dummy',
        'rowCssClass'=>array('row'),
        'ajaxType'=>'post',
        'afterAjaxUpdate'=>"function() {dotdotdot();assignPdfCreation();}",
        'hideHeader'=>true,//	'dataProvider'=>$dataProvider,
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'emptyText'=>'Er zijn geen maaltijden gevonden die voldoen aan uw zoekopdracht',
        'summaryText'=>'({count} resultaten)',
        'pagerCssClass'=>'result-pagination',
        'summaryCssClass'=>'summary',
        'pager'=>array(
            'header' => 'pag',
            'maxButtonCount'=>5,
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
        ),
        'template'=>'<div class="row"><div class="col-md-3">{summary}</div><div class="col-md-9">{pager}</div></div>{items}',
        'columns'=>array (
            array(
                'type'=>'raw',
                'value'=>$image,
                'htmlOptions'=>array('class'=>'col-md-1'),
            ),
            array(
               'type'=>'raw',
               'value'=>$description,
               'htmlOptions'=>array('class'=>'col-md-11 dotted'),
               ),
        )
    )
);

    Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('js/search.js'));

    $script = <<<dot
            function dotdotdot() {

//            alert($(".dotted p").length);
            $(".dotted p.hidden").each(function() {
                $(this).removeClass("hidden");
                $(this).dotdotdot({after: 'a.pdf'});
            });
//            $(".dotted p").dotdotdot();
//            alert('Hello');
            }

            dotdotdot();
            
            $(".component").click(function() {
				var fields = JSON.parse($(this).attr("data"));
				var form = $("#post-search");

				form.find("input[name='Search[productgroep_id]']").val(fields['Search[productgroep_id]']);
				form.find("input[name='Search[maaltijdtype_id]']").val(fields['Search[maaltijdtype_id]']);
				form.find("input[name='Search[maaltijdsubtype_id]']").val(fields['Search[maaltijdsubtype_id]']);
				form.find("input[name='Selected[mainGroup]']").val(fields['Selected[mainGroup]']);
				form.find("input[name='Selected[group]']").val(fields['Selected[group]']);
				form.find("input[name='Selected[subgroup]']").val(fields['Selected[subgroup]']);
				form.find("input[name='Selected[component]']").val(fields['Selected[component]']);
				if (fields['component'].length > 0) {
					field = form.find("input[data-role=component]");
					field.attr('name', 'Search[' + fields['component'] + ']');
				}
				form.submit();
            });
dot;

    Yii::app()->clientScript->registerScript('dotted', $script, CClientScript::POS_READY);

$script=<<<js

function searchValidation() {
}

/**
 * Checks if date is a valid date.
 *
 * @param string date                           Date in format 'dd-mm-yy';
 *
 * @return boolean                              Whethere date represents as valid date.
 */
searchValidation.prototype.validDate = function(date) {

    // string expected with length 8 (dd-mm-yy)
    if (date.length != 8) {
        return false;
    }

    // split in day, month, year
    var dateParts = date.split("-");
    // three parts expected
    if (dateParts.length != 3) {
        return false;
    }

    // can alle parts be parsed to integers
    for (var i=0; i<dateParts.length; i++) {
        if (isNaN(parseInt(dateParts[i], 10))) {
            return false;
        }
    }

    // get day
    var day = parseInt(dateParts[0]);

    // get month, months are zero based
    var month = parseInt(dateParts[1]);
    month--;

    // get year (break for 2000 is 90)
    var year = parseInt(dateParts[2]);
    if (year > 90) {
        year = 1900 + year;
    } else {
        year = 2000 + year;
    }

    // create date
    var dt = new Date(year, month, day);

    // check against initial date parts (must be the same)
    if (dt.getDate() != day || dt.getMonth() != month || dt.getFullYear() != year) {
        return false;
    }

    // passed all checks
    return true;
}

/**
 * Checks date and displays a message if date is not valid.
 *
 * @param string date                           Date to be checked.
 */
searchValidation.prototype.checkDate = function(date) {
    if (this.validDate(date)) {
        this.displayDateError();
        return true;
    } else {
        this.displayDateError("Geen geldige datum (dd-mm-jj)");
        return false;
    }
}

/**
 * Displays error message for date field.
 * If message is empty, no error will be displayed.
 *
 * @param string message                        Message to display.
 */
searchValidation.prototype.displayDateError = function(message) {
    message = (typeof message === "undefined") ? "" : message;
    var errorPane = $("#error-date");
    var errorMessage = errorPane.find("span.message");

    errorMessage.text(message);

    if (message.length === 0) {
        errorPane.addClass("hidden");
    } else {
        errorPane.removeClass("hidden");
    }
}

window.validation = new searchValidation();
js;

Yii::app()->clientScript->registerScript('date-check', $script, CClientScript::POS_END);

$script=<<<js
window.validation.displayDateError();
$("#send-date").submit(function(event) {
    var date = $("#Search_specificatie_datum");
    if (date.val().length == 0) {
        return;
    }
    if (window.validation.checkDate(date.val())) {
        return;
    } else { 
        date.focus();
        event.preventDefault();
    }
});
js;
Yii::app()->clientScript->registerScript('validation', $script, CClientScript::POS_READY);

    Yii::app()->clientScript->registerScriptFile('//cdn.jsdelivr.net/jquery.dotdotdot/1.6.13/jquery.dotdotdot.min.js',  CClientScript::POS_END );
?>
    </div><!-- second column-->
</div>
