class MyPoint
{
	constructor(id, x, y, w, h, name, mapName, angle, offset, rev, area, partition, type, code)
	{
		this.id        = id;
		this.x         = this.x0 = x;
		this.y         = this.y0 = y;
		this.w         = w || 50;
		this.h         = h || 50;
		this.name      = name;
		this.mapName   = mapName;
		this.angle     = angle;
		this.offset    = offset;
		this.rev       = rev;
		this.area      = area;
		this.partition = partition;
		this.type      = type;
		this.code      = code;
		this.check     = this.checkClicked = false;
		this.cordX     = this.cordY = this.textX = this.textY = 0;
		this.IBN       = this.ZBH = this.xI1 = this.yI1 = this.xH1 = this.yH1 = 0;
	}

	createPoint(ratio, tox, toy)
	{
		push();
		fill(255);
    stroke(51);
		rect( this.cordX, this.cordY, this.w*ratio, this.h*ratio);
		fill(0);
		textSize(10*ratio);
		textAlign(CENTER);
		text(this.name, this.textX, this.textY);
		pop();
	}

	caculateCor(ratio, tox, toy)
	{
		push();
		this.cordX = (this.x - this.w/2)*ratio + tox;
		this.cordY = (this.y - this.h/2)*ratio + toy;
		// this.textX = (this.x - this.w/3)*ratio + tox;
		// this.textY = (this.y + this.h/3)*ratio + toy;
		this.textX = (this.x)*ratio + tox;
		this.textY = (this.y + this.h/4)*ratio + toy;
		pop();
	}

	caculate(x01, y01, x02, y02, ratio) 
	{
      this.IBN = atan(abs(x01 - x02)/abs(y01 - y02)) - PI/6;
      this.ZBH = atan(abs(y01 - y02)/abs(x02 - x01)) - PI/6;
      // xA < xB + yA >= yB (2) // x1 <= x2 + y1 > y2 (2)
      if (x01 <= x02 && y01 > y02) {
        this.xI1 = -sin(this.IBN)*2*ratio + x02;
        this.yI1 = cos(this.IBN)*2*ratio + y02;
        this.yH1 = sin(this.ZBH)*2*ratio + y02;
        this.xH1 = -cos(this.ZBH)*2*ratio + x02;
        
      }
      // xA >= xB && yA < yB (4)// xA >= xB && yA < yB (4)
      if (x01 >= x02 && y01 < y02) {
        this.xI1 = sin(this.IBN)*2*ratio + x02;
        this.yI1 = -cos(this.IBN)*2*ratio + y02;
        this.yH1 = -sin(this.ZBH)*2*ratio + y02;
        this.xH1 = cos(this.ZBH)*2*ratio + x02;
      }
      // xA >= xB + yA >= yB (1)// xA > xB + yA >= yB (1)
      if (x01 > x02 && y01 >= y02) {
        this.xI1 = sin(this.IBN)*2*ratio + x02;
        this.yI1 = cos(this.IBN)*2*ratio + y02;
        this.yH1 = sin(this.ZBH)*2*ratio + y02;
        this.xH1 = cos(this.ZBH)*2*ratio + x02;
      }
      // xA < xB + yA < yB (3)// xA < xB + yA <= yB (3)
      if (x01 < x02 && y01 <= y02) {
        this.xI1 = -sin(this.IBN)*2*ratio + x02;
        this.yI1 = -cos(this.IBN)*2*ratio + y02;
        this.yH1 = -sin(this.ZBH)*2*ratio + y02;
        this.xH1 = -cos(this.ZBH)*2*ratio + x02;
      }
      
    }

	createTiniPoint(ratio, tox, toy)
	{
		push();
		fill(255);
    stroke(51);
		circle((this.x - this.w/2)*ratio + tox, (this.y - this.h/2)*ratio + toy, 10*ratio);
		circle((this.x + this.w/2)*ratio + tox, (this.y - this.h/2)*ratio + toy, 10*ratio);
		circle((this.x + this.w/2)*ratio + tox, (this.y + this.h/2)*ratio + toy, 10*ratio);
		circle((this.x - this.w/2)*ratio + tox, (this.y + this.h/2)*ratio + toy, 10*ratio);
		pop();
	}

	drawLine(ratio, x01, y01, x02, y02)
  {
    push();
    fill(255);
    stroke(51);
    strokeWeight(1);
    this.caculate(x01, y01, x02, y02, ratio);
    line(x01, y01, x02, y02);
    line(x02, y02, this.xI1, this.yI1);
    line(x02, y02, this.xH1, this.yH1);
    pop();
  }

	moveMousePoint(ratio, tox, toy)
	{
		if (mouseX >= (this.x - this.w/2)*ratio + tox && mouseX <= (this.x + this.w/2)*ratio + tox && mouseY >= (this.y - this.h/2)*ratio + toy && mouseY <= (this.y + this.h/2)*ratio + toy) {
			this.check = true;
		} else {
			this.check = false;
		}
	}

	clickedOldPoint(ratio)
	{
		if (mouseX >= this.cordX && mouseX <= this.cordX + (this.w)*ratio && mouseY >= this.cordY && mouseY <= this.cordY + (this.h)*ratio) {
			// console.log('true');
			this.checkClicked = true;
		} else {
			// console.log('fasle');
			this.checkClicked = false;
		}
	}

	newPoint(ratio, tox, toy)
	{
		this.cordX = mouseX - (this.w/2)*ratio;
		this.cordY = mouseY - (this.h/2)*ratio;
		this.textX = mouseX - (this.w/3)*ratio;
		this.textY = mouseY + (this.h/3)*ratio;
	}

	setPoint(ratio, tox, toy) 
	{
		// console.log('set');
		this.x = ((mouseX - tox)/ratio).toFixed(0);
		this.y = ((mouseY - toy)/ratio).toFixed(0);
		$('#modalEditLayoutPoint').modal();
		$('#idPoint').text(this.id);
		$('#namePoint').val(this.name);
		$('#mapName').val(this.mapName);
		$('#angle').val(this.angle);
		$('#offset').val(this.offset);
		$('#xPoint').val(this.x);
		$('#yPoint').val(this.y);
		$('#hPoint').val(this.h);
		$('#wPoint').val(this.w);
		$('#revPoint').val(this.rev);
		$('#areaPoint').val(this.area);
		$('#partitionPoint').val(this.partition);
		$('#masterAgvTypePoint').val(this.type);
		$('#code').val(this.code);
	}

	resetPoint()
	{
		this.x = this.x0;
		this.y = this.y0;
	}
}