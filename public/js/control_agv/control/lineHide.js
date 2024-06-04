class LineHide 
{
	constructor(x1, y1, x2, y2, x3, y3, x4, y4, name, typ, to, xI1, yI1, xH1, yH1)
	{
		this.x1 = x1;
		this.y1 = y1;
		this.x2 = x2;
		this.y2 = y2;
		this.x3 = x3;
		this.y3 = y3;
		this.x4 = x4;
		this.y4 = y4;
		this.name = name;
		this.typ = typ;
		this.to = to;
		this.xI1 = xI1;
		this.yI1 = yI1;
		this.xH1 = xH1;
		this.yH1 = yH1;
		this.check = false;
		this.point = false;
		this.drawLine = false;
		this.color = '100000';
		this.ZBH = this.IBN = 0;
	}

	createLine(ratio, tox, toy) 
	{
		push();
		stroke('#'+this.color);
		strokeWeight(3);
  		line(this.x1*ratio + tox,this.y1*ratio + toy, this.x2*ratio + tox, this.y2*ratio + toy); // |
  		line(this.x2*ratio + tox, this.y2*ratio + toy, this.x3*ratio + tox, this.y3*ratio + toy); // /
  		line(this.x2*ratio + tox, this.y2*ratio + toy, this.x4*ratio + tox, this.y4*ratio + toy); // \
  		if (this.xI1 != 0 && this.yI1 != 0 && this.xH1 != 0 && this.yH1 !=0) 
  		{
	  		line(this.x1*ratio + tox, this.y1*ratio + toy, this.xI1*ratio + tox, this.yI1*ratio + toy); // /
	  		line(this.x1*ratio + tox, this.y1*ratio + toy, this.xH1*ratio + tox, this.yH1*ratio + toy); // \
	  	}
  		pop();
	}

	// createPointLine(ratio, tox, toy) 
	// {
	// 	push();
	// 	fill(255);
	// 	stroke(51);
	// 	ellipse(this.x1*ratio + tox,this.y1*ratio + toy, 1.5*ratio);
	// 	ellipse(this.x2*ratio + tox,this.y2*ratio + toy, 1.5*ratio);
	// 	pop();
	// }

	editColor(col) 
	{
		this.color = col;
	}
	
	// moveLine(x1, y1, x2, y2, x3, y3, x4, y4) 
	// {
	// 	// console.log(x1, y1, x2, y2, x3, y3, x4, y4);
	// 	if (x1 != '0') {this.x1 = x1;}
	// 	if (y1 != '0') {this.y1 = y1;}
	// 	if (x2 != '0') {this.x2 = x2;}
	// 	if (y2 != '0') {this.y2 = y2;}
	// 	if (x3 != '0') {this.x3 = x3;}
	// 	if (y3 != '0') {this.y3 = y3;}
	// 	if (x4 != '0') {this.x4 = x4;}
	// 	if (y4 != '0') {this.y4 = y4;}
	// }

	// clickedLine(ratio, tox, toy) 
	// {
	// 	let b = int(dist(this.x2*ratio + tox, this.y2*ratio + toy, mouseX, mouseY));
	// 	let a = int(dist(this.x1*ratio + tox, this.y1*ratio + toy, mouseX, mouseY));
	// 	let m = int(dist(this.x1*ratio + tox,this.y1*ratio + toy, this.x2*ratio + tox, this.y2*ratio + toy));
	// 	let d = int(sin(acos((b*b + m*m - a*a)/(2*b*m)))*b);
	// 	let angleB = PI - acos((b*b + m*m - a*a)/(2*b*m));
	// 	let angleA = PI - acos((a*a + m*m - b*b)/(2*a*m));
	// 	// console.log(abs(d) + '-----' + this.name);
	// 	if (abs(d) <= ratio*2 && angleB >= PI/2 && angleA >= PI/2 || a+b == m && d == 0) 
	// 	{
	// 		this.point = true;
	//     } else 
	//     {
	//         this.point = false;
	//     }
	// }

	// statusPointLine() 
	// {
 //      return this.point;
 //    }
}