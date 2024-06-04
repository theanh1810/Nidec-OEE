class ImgMap
{
	constructor(img, x, y, w, h)
	{
		this.img = img;
		this.x = this.x0 = x;
		this.y = this.y0 = y;
		this.w = w;
		this.h = h;
    this.corX = x;
    this.corY = y;
	}

	createImg(ratio) 
  {
  	image(this.img, this.corX, this.corY, this.w*ratio, this.h*ratio);
  }

  caculateXY(ratio, tox, toy) 
  {
    this.corX = this.x*ratio + tox;
    this.corY = this.y*ratio + toy;
  }
}