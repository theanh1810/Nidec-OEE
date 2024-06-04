/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./resources/js/control_agv/control/imgAgv.js ***!
  \****************************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var ImgAgv = /*#__PURE__*/function () {
  function ImgAgv(img, name, id, x, y, w, h, color, angle, trans, position, positionPnext, proc, battery, destination, code, direction, task, codePrext, diff, regime, timePing, action, status, route, avoid, dsistance) {
    _classCallCheck(this, ImgAgv);

    this.id = id;
    this.img = img;
    this.name = name;
    this.x = this.x0 = this.stepX = x;
    this.y = this.y0 = this.stepY = y;
    this.w = w;
    this.h = h;
    this.color = color || '000';
    this.angle = 0;
    this.run = this.checkX = this.checkY = false;
    this.check1 = this.check2 = this.check3 = this.check4 = false;
    this.vX = this.vY = 0;
    this.xI1 = this.yI1 = this.xH1 = this.yH1 = this.IBN = this.ZBH = 0;
    this.calAng = 0;
    this.trans = trans;
    this.position = position;
    this.positionPnext = positionPnext;
    this.process = proc;
    this.battery = battery;
    this.destination = destination;
    this.code = code;
    this.direction = direction;
    this.task = task;
    this.codePrext = codePrext;
    this.diff = diff;
    this.regime = regime;
    this.timePing = timePing;
    this.action = action;
    this.status = status;
    this.route = route;
    this.avoid = avoid;
    this.distance = dsistance;
    this.myW = 30;
    this.myH = 40;
    this.rectChilW1 = this.myW;
    this.rectChilH1 = 10;
  } // create new img agv


  _createClass(ImgAgv, [{
    key: "createAgv1",
    value: function createAgv1(ratio, tox, toy) {
      push(); // tint('#'+this.color);

      translate(this.x * ratio + tox, this.y * ratio + toy);
      rotate(this.angle); // image(this.img, (-this.w/2)*ratio, (-this.h/2)*ratio, this.w*ratio, this.h*ratio);
      // image(this.img, (-this.w/2), (-this.h/2), this.w, this.h);

      rect(-this.w / 2, -this.h / 2, 30, 40, 3);
      noFill();
      fill('#F69A0F');
      rect(-this.w / 2, -this.h / 2, 30, 10, 3, 3, 0, 0);
      rect(-this.w / 2, -this.h / 2 + 30, 30, 10, 0, 0, 3, 3);
      noFill();
      fill('#000');
      rect(-this.w / 2 + 8, -this.h / 2, 15, 5, 3, 3, 0, 0);
      noFill(); // noTint(z);

      fill('#' + this.color); // textSize(6*4*ratio);

      textSize(4 * 2);
      textAlign(CENTER, CENTER); // text(this.name, (this.x - 2.5*2)*ratio + tox, (this.y + 2*2)*ratio + toy);

      text(this.name, -this.w / 2 + 15, -this.h / 2 + 20); // text(this.name, -this.w/2, -this.h/2);

      noFill();
      pop();
    }
  }, {
    key: "createAgv",
    value: function createAgv(ratio, tox, toy) {
      var xNew = this.x * ratio + tox - this.myW / 2;
      var yNew = this.y * ratio + toy - this.myH / 2;
      var w = 0;
      var h = 0;
      push();

      if (this.direction == '1' || this.direction == '3') {
        w = 30;
        h = 40;
        rect(xNew, yNew, w, h, 3); // Hình Mẹ

        noFill();
        fill('#F69A0F');
        rect(xNew, yNew, 30, 10, 3, 3, 0, 0); // Hình con bên trên

        rect(xNew, yNew + w, 30, 10, 0, 0, 3, 3); // Hình con bên dưới

        noFill();
        fill('#000');

        if (this.direction == '1') {
          rect(xNew + 15 / 2, yNew, 15, 5, 3, 3, 0, 0); // Hình màu đen
        } else {
          rect(xNew + 15 / 2, yNew + h - 5, 15, 5, 3, 3, 0, 0); // Hình màu đen
        }

        noFill();
      } else {
        w = 40;
        h = 30;
        rect(xNew, yNew, w, h, 3); // Hình Mẹ

        noFill();
        fill('#F69A0F');
        rect(xNew, yNew, 10, 30, 3, 0, 0, 3); // Hình con bên trên

        rect(xNew + w - 10, yNew, 10, 30, 0, 3, 3, 0); // Hình con bên dưới

        noFill();
        fill('#000');

        if (this.direction == '2') {
          rect(xNew + w - 5, yNew + 15 / 2, 5, 15, 0, 3, 3, 0); // Hình màu đen
        } else {
          rect(xNew, yNew + h / 2 - 15 / 2, 5, 15, 3, 0, 0, 3); // Hình màu đen
        }

        noFill();
      }

      fill('#' + this.color);
      textSize(4 * 2);
      textAlign(CENTER, CENTER);
      text(this.name, xNew + w / 2, yNew + h / 2);
      noFill();
      pop();
    }
  }, {
    key: "oldCreate",
    value: function oldCreate() {
      var xNew = this.x * ratio + tox - this.myW / 2;
      var yNew = this.y * ratio + toy - this.myH / 2;
      rect(xNew, yNew, this.myW, this.myH, 3); // Hình Mẹ

      noFill();
      fill('#F69A0F');
      rect(xNew, yNew, this.rectChilW1, this.rectChilH1, 3, 3, 0, 0); // Hình con bên trên

      rect(xNew, yNew + 30, this.rectChilW1, this.rectChilH1, 0, 0, 3, 3); // Hình con bên dưới

      noFill();
      fill('#000');
      rect(xNew + 8, yNew, 15, 5, 3, 3, 0, 0); // Hình màu đen

      noFill();
      fill('#' + this.color);
      textSize(4 * 2);
      textAlign(CENTER, CENTER);
      text(this.name, xNew + 15, yNew + 20);
      noFill();
    }
  }, {
    key: "updateNew",
    value: function updateNew(x, y, w, h, color, angle, route, direction) {
      this.x0 = x;
      this.y0 = y;
      this.w = w;
      this.h = h;
      this.color = color;
      this.angle = angle;
      this.route = route;
      this.direction = direction;
    }
  }, {
    key: "updateAngle",
    value: function updateAngle(direction) {
      this.direction = direction;
    }
  }, {
    key: "drawDirection",
    value: function drawDirection(ratio, tox, toy) {
      if (this.run) {
        push();
        stroke('#' + this.color);
        strokeWeight(3);
        this.calculateAngle(this.x, this.y, this.stepX, this.stepY);
        line(this.x * ratio + tox, this.y * ratio + toy, this.stepX * ratio + tox, this.stepY * ratio + toy); // |

        line(this.stepX * ratio + tox, this.stepY * ratio + toy, this.xI1 * ratio + tox, this.yI1 * ratio + toy); // /

        line(this.stepX * ratio + tox, this.stepY * ratio + toy, this.xH1 * ratio + tox, this.yH1 * ratio + toy); // \

        pop();
      }
    }
  }, {
    key: "calculateAngle",
    value: function calculateAngle(x1, y1, x2, y2) {
      this.IBN = atan(abs(x1 - x2) / abs(y1 - y2)) - PI / 6;
      this.ZBH = atan(abs(y1 - y2) / abs(x2 - x1)) - PI / 6; // xA < xB + yA >= yB (2) // x1 <= x2 + y1 > y2 (2)

      if (x1 <= x2 && y1 > y2) {
        this.xI1 = -sin(this.IBN) * 2 + x2;
        this.yI1 = cos(this.IBN) * 2 + y2;
        this.yH1 = sin(this.ZBH) * 2 + y2;
        this.xH1 = -cos(this.ZBH) * 2 + x2;
      } // xA >= xB && yA < yB (4)// xA >= xB && yA < yB (4)


      if (x1 >= x2 && y1 < y2) {
        this.xI1 = sin(this.IBN) * 2 + x2;
        this.yI1 = -cos(this.IBN) * 2 + y2;
        this.yH1 = -sin(this.ZBH) * 2 + y2;
        this.xH1 = cos(this.ZBH) * 2 + x2;
      } // xA >= xB + yA >= yB (1)// xA > xB + yA >= yB (1)


      if (x1 > x2 && y1 >= y2) {
        this.xI1 = sin(this.IBN) * 2 + x2;
        this.yI1 = cos(this.IBN) * 2 + y2;
        this.yH1 = sin(this.ZBH) * 2 + y2;
        this.xH1 = cos(this.ZBH) * 2 + x2;
      } // xA < xB + yA < yB (3)// xA < xB + yA <= yB (3)


      if (x1 < x2 && y1 <= y2) {
        this.xI1 = -sin(this.IBN) * 2 + x2;
        this.yI1 = -cos(this.IBN) * 2 + y2;
        this.yH1 = -sin(this.ZBH) * 2 + y2;
        this.xH1 = -cos(this.ZBH) * 2 + x2;
      }
    }
  }, {
    key: "calculateAngleAuto",
    value: function calculateAngleAuto() {
      return '';
      this.calAng++;
      var AB = sqrt((this.x - this.x0) * (this.x - this.x0) + (this.y - this.y0) * (this.y - this.y0));
      var OA = sqrt(this.x0 * this.x0 + this.y0 * this.y0);
      var OB = sqrt(this.x * this.x + this.y * this.y);
      var OAB = Math.acos((OA * OA + AB * AB - OB * OB) / (2 * OA * AB)) - Math.PI / 2;
      console.log(OAB, this.x, this.x0, this.y, this.y0);
      this.angle = -OAB * 180 / Math.PI;
    }
  }, {
    key: "start",
    value: function start() {
      if (this.run) {
        this.x = this.x0;
        this.y = this.y0; // if (this.calAng == '0') 
        // {
        // 	this.calculateAngleAuto();
        // }
        // let myRatio = ratio < 0.9 ? 1/ratio : ratio; // Di chyen theo ti le ratio
        // // console.log(myRatio);
        // // caculate ratio move
        // if (abs(this.x - this.stepX) > abs(this.y - this.stepY)) 
        // {
        // 	this.vY = (4*2*abs(this.y - this.stepY)/abs(this.x - this.stepX))*myRatio;
        // 	this.vX = (4*2)*myRatio;
        // } else if (abs(this.x - this.stepX) < abs(this.y - this.stepY)) 
        // {
        // 	this.vX = (4*2*abs(this.x - this.stepX)/abs(this.y - this.stepY))*myRatio;
        // 	this.vY = (4*2)*myRatio;
        // } else 
        // {
        // 	this.vX = (4*2)*myRatio;
        // 	this.vY = (4*2)*myRatio;
        // }
        // // move X
        // if (this.x < this.stepX && this.stepX != NaN && !this.check2 || this.check1) 
        // {
        // 	this.check1 = true;
        // 	if (!(this.x >= this.stepX)) 
        // 	{
        // 		this.x = this.x + this.vX;
        // 	} else
        // 	{
        // 		this.checkX = true;
        // 		// this.check1 = false;					
        // 	}
        // } else if (this.x > this.stepX && this.stepX != NaN && !this.check1 || this.check2) 
        // {
        // 	this.check2 = true;
        // 	if (!(this.x <= this.stepX)) 
        // 	{
        // 		this.x = this.x - this.vX;
        // 	} else
        // 	{
        // 		this.checkX = true;
        // 		// this.check2 = false;					
        // 	}
        // } 
        // // move Y
        // if (this.y < this.stepY && this.stepY != NaN && !this.check4 || this.check3) 
        // {
        // 	this.check3 = true;
        // 	if (!(this.y >= this.stepY)) 
        // 	{
        // 		this.y = this.y + this.vY;
        // 	} else
        // 	{
        // 		this.checkY = true;
        // 		// this.check3 = false;					
        // 	}
        // } else if (this.y > this.stepY && this.stepY != NaN && !this.check3 || this.check4) 
        // {
        // 	this.check4 = true;
        // 	if (!(this.y <= this.stepY)) 
        // 	{
        // 		this.y = this.y - this.vY;
        // 	} else
        // 	{
        // 		this.checkY = true;
        // 		// this.check4 = false;
        // 	}
        // }
        // if (this.x == this.stepX && this.y == this.stepY || this.checkX && this.checkY)
        // {
        // 	this.x      = this.x0;
        // 	this.vX     = this.vY = 0;
        // 	this.check1 = this.check2 = this.check3 = this.check4 = this.checkX = this.checkY = false;
        // 	this.run    = false;
        // 	this.calAng = 0;
        // }
      }
    }
  }]);

  return ImgAgv;
}();
/******/ })()
;