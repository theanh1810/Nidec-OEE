/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./resources/js/control_agv/control/imgMap.js ***!
  \****************************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var ImgMap = /*#__PURE__*/function () {
  function ImgMap(img, x, y, w, h) {
    _classCallCheck(this, ImgMap);

    this.img = img;
    this.x = this.x0 = x;
    this.y = this.y0 = y;
    this.w = w;
    this.h = h;
    this.corX = x;
    this.corY = y;
  }

  _createClass(ImgMap, [{
    key: "createImg",
    value: function createImg(ratio) {
      image(this.img, this.corX, this.corY, this.w * ratio, this.h * ratio);
    }
  }, {
    key: "caculateXY",
    value: function caculateXY(ratio, tox, toy) {
      this.corX = this.x * ratio + tox;
      this.corY = this.y * ratio + toy;
    }
  }]);

  return ImgMap;
}();
/******/ })()
;