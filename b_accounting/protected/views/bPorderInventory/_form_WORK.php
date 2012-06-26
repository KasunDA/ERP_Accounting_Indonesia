<?php
Yii::app()->clientScript->registerScript('knockout', "


////////AUTOCOMPLETE
ko.bindingHandlers.autocomplete = {
        init: function (element, params) {
            var options = params().split(' ');
            $(element).bind(\"focus\", function () {
                $(element).change();
            });
            $(element).autocomplete({ 
			source: function( request, response ) {
				$.ajax({
					url: \""  .$this->createUrl('/tAccount/AccountAutoComplete'). "\",
					dataType: \"json\",
					data: {
						term: request.term
					},
					success: function( data ) {
						response($.map( data, function(item) {
							  return {
								label: item,
								value: item
							  }
						}));
					}
				})
			},
            });
        },
        update: function (element, params) {
        }
    };
////////AUTOCOMPLETE

var GiftModel = function(gifts) {
    var self = this;
    self.gifts = ko.observableArray(gifts);
 
    self.addGift = function() {
        self.gifts.push({
            item_id: \"\",
            description: \"\",
            qty: \"\",
            amount: \"\"
        });
    };
 
    self.removeGift = function(gift) {
        self.gifts.remove(gift);
    };
 
    self.save = function(form) {
        //alert(ko.utils.stringifyJson(self.gifts));
        // To actually transmit to server as a regular form post, write this: ko.utils.postJson($(\"form\")[0], self.gifts);
		//ko.utils.postJson(location.href, { gifts: self.gifts });  // from sample
		ko.utils.postJson($(\"form\"), { gifts: self.gifts });  
    };
};
 
var viewModel = new GiftModel([
    { item_id: \"\", description: \"\", qty: \"\", amount: \"\"},
]);


ko.applyBindings(viewModel);
 
// Activate jQuery Validation
$(\"form\").validate({ submitHandler: viewModel.save });




");
?>

					   
<?php $form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(	
		'id'=>'bporder-form',
		'type' => 'horizontal',
		'enableAjaxValidation'=>false,
		//'htmlOptions'=>array('data-bind'=>"submit: save"),
));
?>

<?php echo $form->errorSummary($model); ?>

<div class="control-group">
	<?php echo $form->labelEx($model,'input_date',array("class"=>"control-label")); ?>
	<div class="controls">
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'value'=>CTimestamp::formatDate('yyyy-MM-dd',$model->input_date),
				'attribute'=>'input_date',
				// additional javascript options for the date picker plugin
				'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'dd-mm-yy',
				),
				'htmlOptions'=>array(
						
				),
		));
		?>
	</div>
</div>

<?php echo $form->dropDownListRow($model,'supplier_id',cSupplier::items()); ?>

<?php echo $form->textAreaRow($model,'remark',array('rows'=>2, 'class'=>'span5')); ?>



    <table data-bind='visible: gifts().length > 0'>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody data-bind='foreach: gifts'>
            <tr>
				<td><input type="text" data-bind="value: item_id, autocomplete: '',uniqueName: true" /></td>
				<td><input type="text" data-bind="value: description, uniqueName: true" /></td>
                <td><input class='required number' data-bind='value: qty, uniqueName: true' /></td>
                <td><input class='required number' data-bind='value: amount, uniqueName: true' /></td>
                <td><a href='#' data-bind='click: $root.removeGift'>Delete</a></td>
            </tr>
        </tbody>
    </table>
 
    <button data-bind='click: addGift'>Add Row</button>
    <button data-bind='enable: gifts().length > 0' type='submit'>Submit</button>

<?php $this->endWidget(); ?>