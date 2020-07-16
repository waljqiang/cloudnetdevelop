<!doctype html>
<html">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>消息推送测试</title>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            11
        </div>
        <script src="https://cdn.bootcss.com/socket.io/2.0.3/socket.io.js"></script>
		<script>
		// 如果服务端不在本机，请把127.0.0.1改成服务端ip
		var socket = io("http://192.168.33.10:9093");
		// 当连接服务端成功时触发connect默认事件
		socket.on("connect", function(){
		    console.log("connect success");
		    //发送信息
		    var time1 = setInterval(function(){
		    	socket.emit("push_oplog_unreads","eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjMzLjEwOjgxNjNcL2F1dGhcL3Rva2VuIiwiaWF0IjoxNTk0MDI3ODE3LCJleHAiOjE1OTQwMzUwMTcsIm5iZiI6MTU5NDAyNzgxNywianRpIjoiZDM5NzQ1WGFyRE5HREpmaCIsInN1YiI6MiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.-zE7-Htq_RmJrjIUGALkyhKpj1XAIiTKNryfs08c1N8");
		    },1000);

			socket.on("push_oplog_unreads",function(response){
				response = JSON.parse(response);
				if(response.status == 10000){
					console.log("未读消息数" + response.data);
				}else{
					clearInterval(time1);
					socket.disconnect();
				}
			});
		});
		</script>
    </body>
</html>
