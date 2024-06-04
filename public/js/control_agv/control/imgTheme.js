class ImgTheme
{
	constructor(x) 
  {
    this.x = x;
    this.y = 0;
    this.w = 45;
    this.h = 40;
    this.r0 = 12;
		this.check = false;
    this.checkMap = false;
    this.lineStatus = false; // Khi tao quna he giua cac diem
    this.pointStatus = false;
    this.addPoint    = false;
    this.linePoint   = false;
    this.mV = this.mLV = this.mP = this.mLP = false;
		this.r = this.z = 5;
		this.iText = 2;
		this.ratio0 = 1;
    this.name;
    this.position;
    this.code;
    this.code2;
    this.N1;
    this.N2;
    this.N3;
    this.N4;
    this.N5;
    this.N0;
    this.xLida;
    this.yLida;
    this.xNav;
    this.yNav;
    this.layout;
    this.xI1 = this.yI1 = this.xH1 = this.yH1 = this.IBN = this.ZBH = 0;
    this.colorLine = this.colorMap = this.colorPoint = this.colorLinePoint = '#BBBBBB';
	}
  // Map
	buttonCreate() 
  {
    push();
		stroke(0);
		fill(this.colorMap);
  	rect(this.x, this.y, this.w, this.h, 5);
    ellipse(this.x + this.w/2, this.y + this.h/2, 2*this.r0, 2*this.r0);
  	// fill(0);
  	// textSize(12);
  	// text('Map', this.x + 10, 25);
  	noStroke();
    pop();
  }
  // line Map
  buttonCreateLine() 
  {
    push();
    stroke(0);
    fill(this.colorLine);
    rect(this.x, 42, 45, 40, 5);
    ellipse(this.x + this.w/4, this.y + 42 + 4*this.h/6, this.r0, this.r0);
    line(this.x + this.w/4 + this.r0/2, this.y + 42 + 4*this.h/6, this.x + 3*this.w/4, this.y + 42 + 4*this.h/6);
    line(this.x + this.w/4 + this.r0/2, this.y + 42 + 4*this.h/6, this.x + this.w/4 + this.r0/2 + 4, this.y + 42 + 4*this.h/6 - 4);
    line(this.x + this.w/4 + this.r0/2, this.y + 42 + 4*this.h/6, this.x + this.w/4 + this.r0/2 + 4, this.y + 42 + 4*this.h/6 + 4);
    
    line(this.x + 3*this.w/4, this.y + 42 + 4*this.h/6, this.x + 3*this.w/4, this.y + 42 + 4*this.h/8);
    line(this.x + 3*this.w/4, this.y + 42 + 4*this.h/8, this.x + 3*this.w/4 - 4, this.y + 42 + 4*this.h/8 + 4);
    line(this.x + 3*this.w/4, this.y + 42 + 4*this.h/8, this.x + 3*this.w/4 + 4, this.y + 42 + 4*this.h/8 + 4);
    
    ellipse(this.x + 3*this.w/4, this.y + 42 + 4*this.h/8 - this.r0/2, this.r0, this.r0);
    // fill(0);
    // textSize(12);
    // text('LMap', this.x + 10, 67);
    noStroke();
    pop();
  }
  // Point
  buttonCreatePoint() 
  {
    push();
    stroke(0);
    fill(this.colorPoint);
    rect(this.x, this.y + 84, this.w, this.h, 5);
    rect(this.x + this.w/4,this.y + this.h/4 + 84, this.w/2, this.h/2);
    // fill(0);
    // textSize(12);
    // text('Point', this.x + 10, 108);
    noStroke();
    pop();
  }
  // Line Point
  buttonCreateLinePoint() 
  {
    push();
    stroke(0);
    fill(this.colorLinePoint);
    rect(this.x, 126, 45, 40, 5);
    rect(this.x - 3 + this.w/4, this.y + 126 + this.h/2, this.w/4, this.h/4);

    line(this.x - 3 + this.w/2, this.y + 126 + 5*this.h/8, this.x - 3 + 3*this.w/4, this.y + 126 + 5*this.h/8);
    line(this.x - 3 + this.w/2, this.y + 126 + 5*this.h/8, this.x - 3 + this.w/2 + 4, this.y + 126 + 5*this.h/8 - 4);
    line(this.x - 3 + this.w/2, this.y + 126 + 5*this.h/8, this.x - 3 + this.w/2 + 4, this.y + 126 + 5*this.h/8 + 4);

    line(this.x - 3 + 3*this.w/4, this.y + 126 + 5*this.h/8, this.x - 3 + 3*this.w/4, this.y + 126 + 3*this.h/8);
    line(this.x - 3 + 3*this.w/4, this.y + 126 + 3*this.h/8, this.x - 3 + 3*this.w/4 - 4, this.y + 126 + 3*this.h/8 + 4);
    line(this.x - 3 + 3*this.w/4, this.y + 126 + 3*this.h/8, this.x - 3 + 3*this.w/4 + 4, this.y + 126 + 3*this.h/8 + 4);

    ellipse(this.x - 3 + 3*this.w/4, this.y + 126 + 3*this.h/8 - this.r0/2, this.r0, this.r0);
    // fill(0);
    // textSize(12);
    // text('LPoint', this.x + 5, 148);
    noStroke();
    pop();
  }

  moveMap()
  {
    if (mouseX >= this.x && mouseX <= this.x + this.w && mouseY >= this.y && mouseY <= this.y + this.h) {
      this.mV = true;
    } else
    {
      this.mV = false;
    }
  }
  moveLineMap()
  {
    if (mouseX >= this.x && mouseX <= this.x + this.w && mouseY >= this.y + 42 && mouseY <= this.y + 42 + this.h) {
      this.mLV = true;
      // console.log('OK');
    } else
    {
      this.mLV = false;
    }
  }
  movePoint()
  {
    if (mouseX >= this.x && mouseX <= this.x + this.w && mouseY >= this.y + 84 && mouseY <= this.y + 84 + this.h) {
      this.mP = true;
      // console.log('OK1');
    } else
    {
      this.mP = false;
    }
  }
  moveLinePoint()
  {
    if (mouseX >= this.x && mouseX <= this.x + this.w && mouseY >= this.y + 126 && mouseY <= this.y + 126 + this.h) {
      this.mLP = true;
      // console.log('OK2');
    } else
    {
      this.mLP = false;
    }
  }
  drawTextMap()
  {
    if (this.mV) {
      push();
      stroke(0);
      rect(6*this.x/7, this.w/2, this.w*4, this.h/2, 3);
      fill(0);
      textSize(12);
      text('Vẽ map', 6*this.x/7 + 10, 3*this.w/4 + 3);
      pop();
    }
  }
  drawTextLineMap()
  {
    if (this.mLV) {
      push();
      stroke(0);
      rect(6*this.x/7, this.w/2 + 42, this.w*4, this.h/2, 3);
      fill(0);
      textSize(12);
      text('Vẽ quan hệ giữa map và map', 6*this.x/7 + 10, 3*this.w/4 + 45);
      pop();
    }
  }
  drawTextPoint()
  {
    if (this.mP) {
      push();
      stroke(0);
      rect(6*this.x/7, this.w/2 + 84, this.w*4, this.h/2, 3);
      fill(0);
      textSize(12);
      text('Vẽ point', 6*this.x/7 + 10, 3*this.w/4 + 84 + 3);
      pop();
    }
  }
  drawTextLinePoint()
  {
    if (this.mLP) {
      push();
      stroke(0);
      rect(6*this.x/7, this.w/2 + 126, this.w*4, this.h/2, 3);
      fill(0);
      textSize(12);
      text('Vẽ quan hệ giữa point và map', 6*this.x/7 + 10, 3*this.w/4 + 126 + 3);
      pop();
    }
  }

	setMapStatus() 
  {
		this.check = !this.check;
    if (this.check) 
    {
      this.colorMap = '#44EC60';
    } else 
    {
      this.colorMap = '#BBBBBB';
    }
	}

	status() 
  {
		return this.check;
	}

  statusPoint() 
  {
    this.pointStatus = !this.pointStatus;
  }

  statusAddPoint() 
  {
    this.addPoint = !this.addPoint;
    
    if (this.addPoint) 
    {
      this.colorPoint = '#44EC60';
    } else 
    {
      this.colorPoint = '#BBBBBB';
    }
  }

  statusLinePoint() 
  {
    this.linePoint = !this.linePoint;
    
    if (this.linePoint) 
    {
      this.colorLinePoint = '#44EC60';
    } else 
    {
      this.colorLinePoint = '#BBBBBB';
    }
  }

  setLineStatus() 
  {
    this.lineStatus = !this.lineStatus;
    if (this.lineStatus) 
    {
      this.colorLine = '#44EC60';
    } else 
    {
      this.colorLine = '#BBBBBB';
    }
  }

	drawElip(ratio) 
  {
		if (this.check) 
    {
      push();
			stroke(0);
			fill(255);
			ellipse(mouseX, mouseY, this.r*ratio);
      noFill();
      pop();
			return this.r;
		}
	}

  drawRect(ratio) 
  {
    push();
    stroke(0);
    fill(255);
    rect(mouseX - 15/2*ratio, mouseY - 15/2*ratio, 15*ratio, 15*ratio);
    pop();
  }

  setCheckMap() 
  {
    this.checkMap = !this.checkMap;
  }

  updatePoint() 
  {
    return this.checkMap;
  }

  resetLine() 
  {
    this.lineStatus = false;
    this.colorLine = '#BBBBBB';
  }

  resetMap() 
  {
    this.check = false;
    this.colorMap = '#BBBBBB';
  }

  resetPoint() 
  {
    this.addPoint = false;
    this.colorPoint = '#BBBBBB';
  }

  resetLinePoint() 
  {
    this.linePoint = false;
    this.colorLinePoint = '#BBBBBB';
  }

  drawElipNew(ratio, name, iTextX, iTextY, code, i, N1, N2, N3, N4, N5, N0, xLida, yLida, xNav, yNav, layout, z) 
  {
      this.name = name;
      this.position = i;
      this.code = code;
      // this.code2 = code2;
      this.N1    = N1;
      this.N2    = N2;
      this.N3    = N3;
      this.N4    = N4;
      this.N5    = N5;
      this.N0    = N0;
      this.xLida = xLida;
      this.yLida = yLida;
      this.xNav  = xNav;
      this.yNav  = yNav;
      this.layout= layout;
      this.z = z;
      push();
      stroke(0);
      fill(255);
      strokeWeight(1.5*ratio);
      ellipse(mouseX, mouseY, this.r*ratio);
      fill(0);
      textSize(2*ratio);
      text(name, mouseX - iTextX*ratio, mouseY + iTextY*ratio);
      pop();
  }

}