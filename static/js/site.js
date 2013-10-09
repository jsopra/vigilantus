
$(document).ready(function(){
	$('a[rel="tooltip"]').tooltip();
/*
	function buildGrids(gridPixelSize, color, gap, div)
	{
		var canvas = $('#'+div+'').get(0);
		var ctx = canvas.getContext("2d");

		ctx.lineWidth = 0.1;
		ctx.strokeStyle = color;


		// horizontal grid lines
		for(var i = 0; i <= canvas.height; i = i + gridPixelSize)
		{
			ctx.beginPath();
			ctx.moveTo(0, i);
			ctx.lineTo(canvas.width, i);
			if(i % parseInt(gap) == 0) {
				ctx.lineWidth = 0.2;
			} else {
				ctx.lineWidth = 0.2;
			}
			ctx.closePath();
			ctx.stroke();
		}

		// vertical grid lines
		for(var j = 0; j <= canvas.width; j = j + gridPixelSize)
		{
			ctx.beginPath();
			ctx.moveTo(j, 0);
			ctx.lineTo(j, canvas.height);
			if(j % parseInt(gap) == 0) {
				ctx.lineWidth = 0.2;
			} else {
				ctx.lineWidth = 0.2;
			}
			ctx.closePath();
			ctx.stroke();
		}

	}

	buildGrids(100, "#EEEEEE", 50, "myCanvas");
	*/
   
   var c = document.createElement('canvas'),        
    ctx = c.getContext('2d'),
    cw = c.width = 200,
    ch = c.height = 200;

for( var x = 0; x < cw; x++ ){
    for( var y = 0; y < ch; y++ ){
        ctx.fillStyle = 'hsl(0, 0%, ' + ( 100 - ( Math.random() * 15 ) ) + '%)';
        ctx.fillRect(x, y, 1, 1);
    }
}

document.body.style.background = 'url(' + c.toDataURL() + ')';
});