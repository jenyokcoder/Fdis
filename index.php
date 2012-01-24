<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Search_Context Database and Files</title>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript">

		var $ = jQuery.noConflict();
		var iterator = 0;
		$(document).ready(function(){		   var ToChild = new Array();
		   var Parent  = new Array();      // C:/DISK_E/server/www/bitrix
		   reCursion('E:/wamp/www/bitrix ',0);		});
		function reCursion(url,log){			   if(typeof url=='undefined') return $("body > div:first").html("url = "+typeof url);
	           $.post('this.php','path='+url,function(data){
	           		obj = eval("("+data+")"); var f=obj.files,d=obj.folders,strFiles="",strFolder="",s=obj.start,finish=obj.searchResult,childCnt=0;
	           		if(f.length > 0){
		           		for(k in f){
		                     strFiles += f[k]+"<br />";
		           		}
	           		}

	           		for(key in d){
	           			 if(log==0){ Parent = d; cnt=Parent.length; }

	           			 ToChild = d;
	                     strFolder += d[key]+"<br />";
	           		}
	           		$("body > div:first").html(strFiles+"<hr />"+strFolder);   //data
	           		if(s=='true'){						//if(iterator<2) reCursion(ToChild[0],1);
						console.log(ToChild[0]);
						// for(keys in ToChild){							// var cc = ToChild.length-1;
							// forEach(cc);						// }
	           		}else{	    				iterator++;
			           	if(cnt==iterator) return $("body > div:first").html(finish);
			           	else return reCursion(Parent[iterator],1);
	           		}
	           });
		}
		function forEach(g){			setTimeout(function(){				//console.log(cc);				reCursion(ToChild[g],1);			},500);		}
	</script>
	<style>
	* {
		margin: 0;
		padding: 0;
	}
	body > div:first-child{		margin:50px auto auto auto;
		width:960px;
		height:560px;
		border:1px solid #ccc;
		/*text-align:center;*/
		overflow-x:hidden;
		overflow-y:scroll;	}
	</style>
</head>
<body>
<div>

</div>
</body>
</html>