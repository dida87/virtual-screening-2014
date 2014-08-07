<?php
/* @var $this ProteinController */
/* @var $model Protein */

$this->breadcrumbs=array(
	'Proteins'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Protein', 'url'=>array('index')),
	array('label'=>'Create Protein', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#protein-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(// the dialog
    'id' => 'dialogClassroom',
    'options' => array(
        'title' => 'Protein',
        'autoOpen' => false,
        'modal' => true,
        'width' => 680,
        'height' => 650,
    ),
));
?> 
<div class="divForForm"></div>
<?php $this->endWidget(); ?>

<h1>Manage Proteins</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'protein-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'protein_id',
		'name',
		'file_name',
		'user_id',
		array(
            'header' => 'Update',
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array
                (
                'update' => array(
                    'click' => 'function(){updateClassroom(this); $("#dialogClassroom").dialog("open");return false;}',
                ),
            )
        ),
        array(
            'header' => 'Delete',
            'class' => 'CButtonColumn',
            'template' => '{delete}',
        ),
        array(
            'header' => 'View',
            'class' => 'CButtonColumn',
            'template' => '{view}',
        )
	),
)); 

class MyClass {

    public static function myMethod($data, $row, $component) {
        //the $data is the ActiveRecord Model collection from the dataProvider
        //the $row is the data row
        //the $component is the CGridColumn object for each columns
        // return the result you want
        return $row + 1;
    }

}
?>

<?php echo CHtml::button('Add Protein', // the link for open the dialog
        array(
    //'style' => 'cursor: pointer; text-decoration: underline;',
      'style'=> 'width: 120px; border-radius: 10px;',
      'onclick' => "{addClassroom(); $('#dialogClassroom').dialog('open');}"
));
?>

<?php echo CHtml::button('Advanced Search',array(
     'style'=> 'width: 140px; border-radius: 15px;',
    'class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<script type="text/javascript" src="../first_yii/assets/js_uploadfile/ajaxfileupload.js"></script>
<script type="text/javascript">
    // ajax upload image to server
    function ajaxFileUpload()
    {
        // add code validate here...
        $fileUp = $('input[name=fileToUpload]').val();
        if($fileUp == '') return;

        $.ajaxFileUpload
        (
        {
            url:'protected/views/protein/doajaxfileupload.php',
            secureuri:false,
            fileElementId:'fileToUpload',
            async: false,
            dataType: 'json',
            //data:{
            //    name:'logan', 
            //    id:'id'
            //},
            success: 
            function (data, status)
            {
                if(typeof(data.error) != 'undefined')
                {
                    if(data.error != '')
                    {
                        $("#unsuccessRepoft").show();
                    }
                    else
                    {
                        $('#Protein_file_name').val(data.msg);
                        $("#successRepoft").show();
                    }
                }
            }
            ,
            error: function (data, status, e)
            {
                $("#unsuccessRepoft").show();
            }
        }
    )

        return ;

    }
    
    // here is the magic
    function addClassroom()
    {
        $.ajax({
            url: 'index.php?r=protein/create',
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data){
                if (data.status == 'failure')
                {
                    $('#dialogClassroom div.divForForm').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogClassroom div.divForForm form').submit(
                    addClassroom
                    );
                }
                else
                {
                    //$('#dialogClassroom div.divForForm').html(data.div);
                    $.fn.yiiGridView.update('protein-grid');
                    //setTimeout("$('#dialogClassroom').dialog('close')",3000);
                    
                    $('#dialogClassroom').dialog('close');
                    alert('insert success');
                }
            }
        });
        return false; 
    }
    function updateClassroom(control)
    {
        $.ajax({
            url: $(control).attr('href'),
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data){
                if (data.status == 'failure')
                {
                    $('#dialogClassroom div.divForForm').html(data.div);
                    //Here is the trick: on submit-> once again this function!
                    $('#dialogClassroom div.divForForm form').submit(_updateClassroom);
                }
                else
                {
                    $('#dialogClassroom div.divForForm').html(data.div);
                    $.fn.yiiGridView.update('protein-grid');
                    setTimeout("$('#dialogClassroom').dialog('close')",3000);
                }
            }
        });
        return false; 
    }
    
    function _updateClassroom()
    {
        $.ajax({
            url: 'index.php?r=protein/update&id=0',
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data){
                if (data.status == 'failure')
                {
                    $('#dialogClassroom div.divForForm').html(data.div);
                    $('#dialogClassroom div.divForForm form').submit(_updateClassroom);
                    //Here is the trick: on submit-> once again this function!
                }
                else
                {
                    //$('#dialogClassroom div.divForForm').html(data.div);
                    $.fn.yiiGridView.update('protein-grid');
                    //setTimeout("$('#dialogClassroom').dialog('close')",3000);
                    
                    $('#dialogClassroom').dialog('close');
                    alert('update success');
                }
            }
        });
        return false;
        
    }
    
    function proteinFileOnchange()
    {
        ajaxFileUpload();
        //$("#successRepoft").show();
    }
    
</script>
