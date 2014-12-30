/**
 *  canvas蒙板图片处理插件
 *  -----------------------------
 *  时间：2014-03-21
 *  准则：JS原型
 *********************************************************************************************
 *  别人写的东西，重复利用
 * -----------保持队形------------------
 *  <div id='Lottery'></div>
 *********************************************************************************************/
/**
 * [Lottery description]蒙板插件
 * @param  {DOM}      node       canvas对象父辈
 * @param  {地址值}    url        背景图片或者文字
 * @param  {地址值}    canvas_url 蒙板的图片
 * @param  {DOM类型}   type       蒙板的类型
 * @param  {px}       w          画布的宽度
 * @param  {px}       h          画布的高度
 * @param  {fn}       callback   回调函数
 */
define(function (require, exports, module){
    var $ = require('./zepto');
    
    function Lottery(node, cover, coverType, width, height, drawPercentCallback) {
        //canvas
        this.conNode = node; 

        // 背景canvas
        this.background = null;
        this.backCtx = null;

        // 蒙板canvas
        this.mask = null;
        this.maskCtx = null;

        // 背景图
        this.lottery = null;
        this.lotteryType = 'image';

        // 蒙板图
        this.cover = cover || "#000"; 
        this.coverType = coverType;
        this.pixlesData = null;

        // canvas宽高
        this.width = width;
        this.height = height;

        this.lastPosition = null;
        // 回调函数
        this.drawPercentCallback = drawPercentCallback;

        this.vail = false;
    }

    Lottery.prototype = {
    	// 创建元素
        createElement: function (tagName, attributes) {
            var ele = document.createElement(tagName);
            for (var key in attributes) {
                ele.setAttribute(key, attributes[key]);
            }
            return ele;
        },

        // 获取当前canvas透明像素的百分比
        getTransparentPercent: function(ctx, width, height) {
            // 获取画布的像素点
            var imgData = ctx.getImageData(0, 0, width, height),
                pixles = imgData.data,
                transPixs = [];

            // 计算画布中，透明程度（第四个值为透明度0-255）
            for (var i = 0, j = pixles.length; i < j; i += 4) {
                var a = pixles[i + 3];
                if (a < 128) {
                    transPixs.push(i);
                }
            }
            return (transPixs.length / (pixles.length / 4) * 100).toFixed(2);
        },

        // 重置画布
        resizeCanvas: function (canvas, width, height) {
            canvas.width = width;
            canvas.height = height;
            canvas.getContext('2d').clearRect(0, 0, width, height);
        },

        resizeCanvas_w : function(canvas, width, height){
            canvas.width = width;
            canvas.height = height;
            canvas.getContext('2d').clearRect(0, 0, width, height);

            // canvas画布，生成对应的DOM开始--(前者判断是否生成背景图)
            if(this.vail) this.drawLottery();
            else this.drawMask();
        },

        // 画布上画点
        drawPoint: function (x, y, fresh) {
            this.maskCtx.beginPath();
            this.maskCtx.arc(x, y, 30, 0, Math.PI * 2);
            this.maskCtx.fill();

        	// 蒙板-路径还是记录
            this.maskCtx.beginPath();

            // 画笔大小
            this.maskCtx.lineWidth = 60;
            // 前者是线的末端样式，后者是线连接处的样式---圆
            this.maskCtx.lineCap = this.maskCtx.lineJoin = 'round';

            // 画点
            if (this.lastPosition) {
                this.maskCtx.moveTo(this.lastPosition[0], this.lastPosition[1]);
            }
            this.maskCtx.lineTo(x, y);
            this.maskCtx.stroke();

            this.lastPosition = [x,y];

            this.mask.style.zIndex = (this.mask.style.zIndex == 20) ? 21 : 20;
        },

        // 事件处理
        bindEvent: function () {
            var _this = this;
            // 判断是否为移动端
            var device = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
            var clickEvtName = device ? 'touchstart' : 'mousedown';
            var moveEvtName = device? 'touchmove': 'mousemove';
            if (!device) {
                var isMouseDown = false;
                _this.conNode.addEventListener('mouseup', function(e) {
                    e.preventDefault();

                    isMouseDown = false;
                    var per = _this.getTransparentPercent(_this.maskCtx, _this.width, _this.height);

                    if(per>=50){
                        // 执行回调函数
                        if(typeof(_this.drawPercentCallback)=='function') _this.drawPercentCallback();
                    }
                }, false);
            } else {
                _this.conNode.addEventListener("touchmove", function(e) {
                    if (isMouseDown) {
                        e.preventDefault();
                    }
                    if (e.cancelable) { e.preventDefault(); }else{window.event.returnValue = false;}
                }, false);
                _this.conNode.addEventListener('touchend', function(e) {
                    isMouseDown = false;
                    var per = _this.getTransparentPercent(_this.maskCtx, _this.width, _this.height);
                    if(per>=50){
                        // 执行回调函数
                        if(typeof(_this.drawPercentCallback)=='function') _this.drawPercentCallback();
                    }
                }, false);
            }

            // move事件来画点
            this.mask.addEventListener(clickEvtName, function (e) {
                e.preventDefault();

                // 记录开始move
                isMouseDown = true;

                var x = (device ? e.touches[0].pageX : e.pageX||e.x);
                var y = (device ? e.touches[0].pageY : e.pageY||e.y);

                // 画点
                _this.drawPoint(x, y,isMouseDown);
            }, false);

            this.mask.addEventListener(moveEvtName, function (e) {
                e.preventDefault();

                // 记录是否开始move
                if (!isMouseDown)  return false; 
                e.preventDefault();

                var x = (device ? e.touches[0].pageX : e.pageX||e.x);
                var y = (device ? e.touches[0].pageY : e.pageY||e.y);

                // 画点
                _this.drawPoint(x, y,isMouseDown);
            }, false);
        },

        // 画背景图
        drawLottery: function () {
            if (this.lotteryType == 'image') {
                var image = new Image(),
                    _this = this;
                image.onload = function () {
                    this.width = _this.width;
                    this.height = _this.height;                
                    _this.resizeCanvas(_this.background, _this.width, _this.height);
                    _this.backCtx.drawImage(this, 0, 0, _this.width, _this.height);
                    _this.drawMask();
                }
                image.src = this.lottery;
            } else if (this.lotteryType == 'text') {
                this.width = this.width;
                this.height = this.height;
                this.resizeCanvas(this.background, this.width, this.height);
                this.backCtx.save();
                this.backCtx.fillStyle = '#FFF';
                this.backCtx.fillRect(0, 0, this.width, this.height);
                this.backCtx.restore();
                this.backCtx.save();
                var fontSize = 30;
                this.backCtx.font = 'Bold ' + fontSize + 'px Arial';
                this.backCtx.textAlign = 'center';
                this.backCtx.fillStyle = '#F60';
                this.backCtx.fillText(this.lottery, this.width / 2, this.height / 2 + fontSize / 2);
                this.backCtx.restore();
                this.drawMask();
            }
        },

        // 画蒙板
        drawMask: function() {
            if (this.coverType == 'color') {
                this.maskCtx.fillStyle = this.cover;
                this.maskCtx.fillRect(0, 0, this.width, this.height);
                this.maskCtx.globalCompositeOperation = 'destination-out';
            } else if (this.coverType == 'image'){
                var image = new Image(),
                    _this = this;
                image.onload = function () {
                    _this.resizeCanvas(_this.mask, _this.width, _this.height);

                    var android = (/android/i.test(navigator.userAgent.toLowerCase()));

                    _this.maskCtx.globalAlpha=0.98;
                    // _this.maskCtx.drawImage(this, 0, 0,_this.width, _this.height);
                    _this.maskCtx.drawImage(this,0,0,this.width,this.height,0,0,_this.width,_this.height);
           
                    var fontSize = 50;
                    var txt = $('#ca-tips').val();
                    var gradient=_this.maskCtx.createLinearGradient(0,0,_this.width,0);
                        gradient.addColorStop("0","#fff");
                        gradient.addColorStop("1.0","#000");

                    _this.maskCtx.font = 'Bold ' + fontSize + 'px Arial';
                    _this.maskCtx.textAlign = 'left';
                    _this.maskCtx.fillStyle = gradient;
                    _this.maskCtx.fillText(txt,_this.width/2-_this.maskCtx.measureText(txt).width/2, 100);

                    _this.maskCtx.globalAlpha=1;

                    _this.maskCtx.globalCompositeOperation = 'destination-out';
                }
                image.src = this.cover;
            }
        },

        // 函数初始化
        init: function (lottery, lotteryType) {
            // 判断是否传入背景图参数，并储存值
            // 生成背景图的DOM
            if(lottery){
                this.lottery = lottery;
                this.lottery.width = this.width;
                this.lottery.height = this.height;
                this.lotteryType = lotteryType || 'image';

                this.vail = true;
            }
            if(this.vail){
                this.background = this.background || this.createElement('canvas', {
                    style: 'position:fixed;left:50%;top:0;width:640px;margin-left:-320px;height:100%;background-color:transparent;'
                });
            }

            // 生成蒙板DOM
            this.mask = this.mask || this.createElement('canvas', {
                style: 'position:fixed;left:50%;top:0;width:640px;margin-left:-320px;height:100%;background-color:transparent;'
            });
            this.mask.style.zIndex = 20;

            // 将目标wrapDOM的HTML内容全部清空--(canvas-clear）
            if (!this.conNode.innerHTML.replace(/[\w\W]| /g, '')) {
                if(this.vail) this.conNode.appendChild(this.background);
                this.conNode.appendChild(this.mask);
                this.bindEvent();
            }
            if(this.vail) this.backCtx = this.backCtx || this.background.getContext('2d');
            this.maskCtx = this.maskCtx || this.mask.getContext('2d');

            // canvas画布，生成对应的DOM开始--(前者判断是否生成背景图)
            if(this.vail) this.drawLottery();
            else this.drawMask();

            var _this = this;
            window.addEventListener('resize',function(){
                // canvas宽高
                _this.width = document.documentElement.clientWidth;
                _this.height = document.documentElement.clientHeight;

                _this.resizeCanvas_w(_this.mask, _this.width, _this.height);
            },false);
        }
    }

    module.exports = Lottery;
})
