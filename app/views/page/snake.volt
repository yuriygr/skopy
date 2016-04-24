{{ content() }}

<style type="text/css">
canvas {border:5px dotted #ddd;}
</style>
<script type="text/javascript">
function play_game(){
	var level = 65;	 // Game level, by decreasing will speed up
	var rect_w = 79;	 // Width 
	var rect_h = 35;	 // Height
	var inc_score = 50;	 // Score
	var snake_color = "#34495E";	 // Snake Color
	var ctx;	 // Canvas attributes
	var tn = [];	 // temp directions storage
	var x_dir = [-1, 0, 1, 0];	 // position adjusments
	var y_dir = [0, -1, 0, 1];	 // position adjusments
	var queue = []; 
	var frog = 1;	 // defalut food
	var map = [];
	var MR = Math.random; 
	var X = 5 + (MR() * (rect_w - 10))|0;	// Calculate positions
	var Y = 5 + (MR() * (rect_h - 10))|0;	// Calculate positions
	var direction = MR() * 3 | 0; 
	var interval = 0;
	var score = 0;
	var sum = 0, easy = 0;
	var i, dir;
	// getting play area 
	var c = document.getElementById('playArea');
	ctx = c.getContext('2d');
	// Map positions
	for (i = 0; i < rect_w; i++){
		map[i] = [];
	}
	// random placement of snake food
	function rand_frog(){
		var x, y;
		do {
			x = MR() * rect_w|0;
			y = MR() * rect_h|0;
		} 
		while (map[x][y]);
		map[x][y] = 1;
		ctx.fillStyle = snake_color;
		ctx.strokeRect(x * 10+1, y * 10+1, 8, 8);
	}
	// Default somewhere placement 
	rand_frog();
	function set_game_speed(){
		if (easy) {
			X = (X+rect_w)%rect_w;
			Y = (Y+rect_h)%rect_h;
		}
		--inc_score;
		if (tn.length) {
			dir = tn.pop();
			if ((dir % 2) !== (direction % 2)) {
				direction = dir;
			}
		}
		if ((easy || (0 <= X && 0 <= Y && X < rect_w && Y < rect_h)) && 2 !== map[X][Y]) {
			if (1 === map[X][Y]) {
				score+= Math.max(5, inc_score);
				inc_score = 50;
				rand_frog();
				frog++;
			}
			//ctx.fillStyle("#ffffff");
			ctx.fillRect(X * 10, Y * 10, 9, 9);
			map[X][Y] = 2;
			queue.unshift([X, Y]);
			X+= x_dir[direction];
			Y+= y_dir[direction];
			if (frog < queue.length) {
				dir = queue.pop()
				map[dir[0]][dir[1]] = 0;
				ctx.clearRect(dir[0] * 10, dir[1] * 10, 10, 10);
			}
		}else if (!tn.length){
			var msg_score = document.getElementById("msg");
			msg_score.innerHTML = "Спасибо, что проиграли в игру.<br /> Ваш счёт : <b>"+score+"</b><br /><br /><input type='button' class='btn' value='Сыграть ещё?' onclick='window.location.reload();' />";
			document.getElementById("playArea").style.display = 'none';
			window.clearInterval(interval);
		}
	}

	interval = window.setInterval(set_game_speed, level);
	document.onkeydown = function(e) {
		var code = e.keyCode - 37;
		if (0 <= code && code < 4 && code !== tn[0]) {
			tn.unshift(code);
		}else if (-5 == code){
			if (interval)		{
				window.clearInterval(interval);
				interval = 0;
			}else{
				interval = window.setInterval(set_game_speed, 60);
			}
		}else{ 
			dir = sum + code;
			if (dir == 44||dir==94||dir==126||dir==171) {
				sum+= code
			} else if (dir === 218) easy = 1;
		}
	}
}
</script>
<div id="msg"></div>
<canvas id="playArea" width="790" height="350">Sorry your browser does not support HTML5</canvas><br>
<script type="text/javascript">play_game();</script>

<br>
<br>
<hr>
<h1>Заголовок 1 уровня</h1>
<h2>Заголовок 2 уровня</h2>
<h3>Заголовок 3 уровня</h3>
<h4>Заголовок 4 уровня</h4>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam efficitur scelerisque lorem, id posuere leo accumsan a. Proin et malesuada purus, nec sagittis sapien. In scelerisque <i>fringilla ultrices</i>. Etiam ut dictum ligula. Aliquam ac tortor vel sem venenatis condimentum. Quisque eu nulla leo. Suspendisse aliquet enim at rutrum laoreet. Pellentesque id nulla ligula. In odio sem, porttitor non tellus eget, pulvinar molestie tortor. Nullam non orci vitae ligula egestas placerat ut a ipsum. Aenean semper <b>lorem</b> eget orci congue lacinia.</p>
<hr>
<p>Lorem ipsum dolor sit amet, consectetur <u>adipiscing elit</u>. Aliquam efficitur scelerisque lorem, id posuere leo accumsan a. Proin et malesuada purus, nec sagittis sapien. In scelerisque fringilla ultrices. Etiam ut dictum ligula. Aliquam ac tortor vel sem venenatis condimentum. Quisque eu nulla leo. Suspendisse aliquet enim at rutrum laoreet. Pellentesque id nulla ligula. In odio sem, porttitor non tellus eget, pulvinar molestie tortor. Nullam non orci vitae ligula egestas placerat ut a ipsum. Aenean semper lorem eget orci congue lacinia.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam efficitur scelerisque lorem, id posuere leo accumsan a. Proin et malesuada purus, nec sagittis sapien. In scelerisque fringilla ultrices. Etiam ut dictum ligula. Aliquam ac tortor vel sem venenatis condimentum. Quisque eu nulla leo. Suspendisse aliquet enim at rutrum laoreet. Pellentesque id nulla ligula. In odio sem, porttitor non tellus eget, pulvinar molestie tortor. Nullam non orci vitae ligula egestas placerat ut a ipsum. Aenean semper lorem eget orci congue lacinia.</p>
<hr>
<a href="#" class="btn">Ссылка-кнопка обычная</a> <input type="button" class="btn" value="Кнопка обычная"><br><br>
<a href="#" class="btn btn-gray">Ссылка-кнопка серая</a> <input type="button" class="btn btn-gray" value="Кнопка серая"><br><br>
<a href="#" class="btn btn-cancle">Ссылка-кнопка отмена</a> <input type="button" class="btn btn-cancle" value="Кнопка отмена"><br><br>
<a href="#" class="btn btn-remove">Ссылка-кнопка удалить</a> <input type="button" class="btn btn-remove" value="Кнопка удалить"><br><br>
<a href="#" class="btn btn-big">Ссылка-кнопка большая</a>