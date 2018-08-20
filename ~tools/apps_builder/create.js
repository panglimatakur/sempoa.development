var exec 	= require('child_process').exec; // | /jik

var child 	= exec('cordova create purple sempoa.discoin.purple Purple'); //
child.stdout.on('data', function(data) { 
	console.log('stdout: ' + data); 
	
	var child2 	= exec('copy D:\\xampp\\htdocs\\sempoa.biz\\~tools\\apps_builder\\~android-files\\add_platform.js D:\\xampp\\htdocs\\sempoa.biz\\~tools\\apps_builder\\purple\\add_platform.js');
	child2.stdout.on('data', function(data) { 
		console.log('stdout: ' + data); 		
		//cmd.exe /K "cd /d D:\\xampp\\htdocs\\sempoa.biz\\~tools\\apps_builder\\purple\\&cls"
	});
	
});

exec('start cmd.exe @cmd /k "cd purple"');	
