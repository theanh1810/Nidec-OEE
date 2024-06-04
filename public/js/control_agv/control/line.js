class Line
{
	constructor(x1, y1, x2, y2, x3, y3, x4, y4, name, typ, to)
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
		this.IBN = this.ZBH = this.xI1 = this.yI1 = this.xH1 = this.yH1 = 0;
	}

	createLinePoint(ratio, tox, toy)
	{
		push();
		// console.log('ok');
		strokeWeight(3);
  		line(this.x1*ratio + tox,this.y1*ratio + toy, this.x2*ratio + tox, this.y2*ratio + toy); // |
  		line(this.x2*ratio + tox, this.y2*ratio + toy, this.x3*ratio + tox, this.y3*ratio + toy); // /
  		line(this.x2*ratio + tox, this.y2*ratio + toy, this.x4*ratio + tox, this.y4*ratio + toy); // \

		pop();
	}

	caculate()
	{
		this.IBN = atan(abs(this.x1 - this.x2)/abs(this.y1 - this.y2)) - PI/6;
		this.ZBH = atan(abs(this.y1 - this.y2)/abs(this.x2 - this.x1)) - PI/6;
		// xA < xB + yA >= yB (2) // x1 <= x2 + y1 > y2 (2)
		if (this.x1 <= this.x2 && this.y1 > this.y2) {
		    this.xI1 = -sin(this.IBN)*2 + this.x2;
		    this.yI1 = cos(this.IBN)*2 + this.y2;
		    this.yH1 = sin(this.ZBH)*2 + this.y2;
		    this.xH1 = -cos(this.ZBH)*2 + this.x2;
		}
		// xA >= xB && yA < yB (4)// xA >= xB && yA < yB (4)
		if (this.x1 >= this.x2 && this.y1 < this.y2) {
		    this.xI1 = sin(this.IBN)*2 + this.x2;
		    this.yI1 = -cos(this.IBN)*2 + this.y2;
		    this.yH1 = -sin(this.ZBH)*2 + this.y2;
		    this.xH1 = cos(this.ZBH)*2 + this.x2;
		}
		// xA >= xB + yA >= yB (1)// xA > xB + yA >= yB (1)
		if (this.x1 > this.x2 && this.y1 >= this.y2) {
		    this.xI1 = sin(this.IBN)*2 + this.x2;
		    this.yI1 = cos(this.IBN)*2 + this.y2;
		    this.yH1 = sin(this.ZBH)*2 + this.y2;
		    this.xH1 = cos(this.ZBH)*2 + this.x2;
		}
		// xA < xB + yA < yB (3)// xA < xB + yA <= yB (3)
		if (this.x1 < this.x2 && this.y1 <= this.y2) {
		    this.xI1 = -sin(this.IBN)*2 + this.x2;
		    this.yI1 = -cos(this.IBN)*2 + this.y2;
		    this.yH1 = -sin(this.ZBH)*2 + this.y2;
		    this.xH1 = -cos(this.ZBH)*2 + this.x2;
		}
	}
}