const HOST = 'http://localhost/canvas/';
var canvas = document.querySelector('canvas');

canvas.width = 1000;
canvas.height = 1000;

var c = canvas.getContext('2d');
// c.fillRect(100, 100, 100, 100);

// c.beginPath();
// c.moveTo(100, 200);
// c.lineTo(120, 220);
// c.lineTo(220, 220);
// c.lineTo(220, 120);
// c.lineTo(200, 100);
// c.strokeStyle = "#ff0000";
// c.stroke();


// c.font = '48px cursive';
// c.fillText('Hello world', 50, 100);
// c.font = '48px cursive';
// c.strokeStyle = "#000000";
// c.strokeText('Hello world', 53, 103);

var fontLoaded = [];
var all_fonts = [];
var all_font_loaded = false;
var font_i = 0;
var font_first_run = true;
var size_of_font = null;
var testing_font_sis = false;
var sis, nan;
var sizes = [];
var inicialized = false;
var init = function() {	
	get_fonts(
		function(){
			c.textAlign = "center";
			setInterval(function(){
				if(font_i < all_fonts.length - 1){
					font_i++;
				} else {
					font_i = 0;
				}
				sis = all_fonts[font_i].sis;
				nan = all_fonts[font_i].nome;
				console.log(nan);
				c.font = `${sis}px "${nan}"`;
			}, 1000);
		}
	);
}();

function frame(){
	c.fillText('Hello world', canvas.width/2, sis);
}
function teste_all_loaded(){
	for(let font in fontLoaded){
		if(!font){
			test = false;
		}
	}
}

function ajax(met, url, query, fun = function(){}){
	var http = new XMLHttpRequest();
	http.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			fun(this.responseText);
		}
	}
	http.open(met, url, true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.send(query);
}
function get_fonts(callback){
	var return_fonts = [];
	ajax('POST', 'api/get_fonts.php', '', function(response){
		all_fonts = JSON.parse(response);
		console.log(all_fonts);
		for (var i = 0; i < all_fonts.length; i++) {
			all_fonts[i] = new fontinea(all_fonts[i]);
		}
		callback();
		inicialized = true;
	});
}

var startingTime = lastTime = null;
function anime(currentTime){
	if(!startingTime){startingTime=currentTime;}
	if(!lastTime){lastTime=currentTime;}
	totalElapsedTime=(currentTime-startingTime);
	elapsedSinceLastLoop=(currentTime-lastTime);
	lastTime=currentTime;
	if(elapsedSinceLastLoop > 30){
		console.log(`${font_i} jesus ${elapsedSinceLastLoop} ms`);
	} else if(!font_first_run) {
		console.log(`${font_i} _all_ ${elapsedSinceLastLoop} ms`);
	} else {
		console.log(`${font_i} _____ ${elapsedSinceLastLoop} ms`);
	}
	
	if(inicialized){
		c.clearRect(0,0,canvas.width,canvas.height);
		frame();
	}
	window.requestAnimationFrame(anime);
};
window.requestAnimationFrame(anime);


class fontinea{
	constructor(font){
		this.nome = font;
		this.loaded = false;
		this.testing_sis = true;
		this.sis = 100;
	}
	resis(){
		if(this.testing_sis && this.loaded){
			c.font = `${this.sis}px "${this.nome}"`;
			this.size_of_font = c.measureText('Hello world');
			if(this.size_of_font.width > canvas.width){
				this.sis-=1;
			} else {
				this.testing_sis = false;
			}
		}
	}
}