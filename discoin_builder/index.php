<!DOCTYPE html>
<html>
    <head>
        <title>Ajax Progress</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style type="text/css">
			#count_status{ padding:0 0 10px 0;  }
			.buildiframe{
				border:1px solid #CCC;
				border-radius:3px;
				-moz-border-radius:3px;
				-webkit-border-radius:3px;
				font-family:"Century Gothic";
				font-size:10px;
			}
		</style>
		<script type="text/javascript" src="js/jquery-2.1.1.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>        
        <script src="js/jquery.ajax-progress.js"></script>
        <script>
			function set_bar(e){
				$("#count_status").empty();
				$("#df").html(e+"%");
				$(".progress, #detail").show();
				$("#process").css("width",e+"%");
			}
            $(function() {
				$("#build").on("click",function(){  
					$("#ouput").submit();
					$("#count_status").html("Sedang mengumpulkan data, mohon menunggu sebentar.....");
				})
            });
        </script>
    </head>
    <body>
    	<div class="container" style="margin-top:20px;">
            <div id="count_status"></div>
            <div class="progress" style="display:none"> 
                <div id="process" class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"><div id="df"></div></div> 
            </div>
            <div class="clearfix"></div>
            <form id="ouput"  method="post" action="data/builder.php" target="progress_output" enctype="multipart/form-data">
                <div id="detail" class="col-md-12" style=" display:none">
                    <iframe class="buildiframe" frameborder="0" name="progress_output" style="width:100%; height:400px; overflow:scroll;" >
                        
                    </iframe>
                </div>
                <div class="form-group col-md-6">
                	<label>Nama Aplikasi</label>
                    <input type="text" class="form-control" name="app_name" placeholder="Nama Aplikasi">
                </div>
                <div class="form-group col-md-6">
                	<label>Icon Aplikasi</label>
                	<input type="file" name="icon">
                </div>
                <div class="form-group col-md-12">
                <button type="submit" class="btn btn-danger" id="build" name="build" value="build">
                    Build APK
                </button>
                </div>
            </form>
        </div>
    </body>
</html>
