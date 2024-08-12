<style>
    .left{
        background : #115089;       
        min-height: 650px;
        padding-top: 80px;
    }
    .middle{
        background : lightblue; 
        min-height: 650px;      
        padding-top: 80px;
    }
    .right{
        background : skyblue;       
        min-height: 650px;
        padding-top: 80px;
    }
    .mx{
        display: flex;
    }
</style>
<div class="container-fluid">
	<div class="row form-group">
 		<div class="col-2" id="div-left" style="background-color: #115089; padding-top: 80px; min-height: 650px;"> 		
     		<div class="row form-group" style="padding: 10px"> 
    			<?php require_once './view/docente/panelLeft.php';?>
    		</div>
    	</div>    
    	
 		<div class="col-9" id="div-left" style="background-color: #E8EAFC; padding-top: 80px; min-height: 650px;"> 		
     		<div class="row form-group" style="padding: 10px"> 
    			<?php require_once './view/docente/panelMiddle.php';?>
    		</div>
    	</div>
        
 		<div class="col-1" id="div-left" style="background-color: #C0D9FF; padding-top: 80px; min-height: 650px;"> 		
     		<div class="row form-group" style="padding: 10px"> 
    			<?php require_once './view/docente/panelRight.php';?>
    		</div>
    	</div>    
	</div>
</div>
<?php 
?>