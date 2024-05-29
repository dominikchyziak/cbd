(new function() {
    var l = 36,
        m = 2,
        p = 500,
        q = 600,
        r = 5555,
        u = 200;
    this.COUNT = l;
    this.BAY = m;
    this.Body_width = p;
    this.Body_height = q;
    this.Old_width = this.Body_width;
    this.Old_height = this.Body_height;
    this.speed = 0.4;
    this.MaxZ = 8;
    this.isShowZoonIMG = r > 1 ? true : false;
    this.autospeed = u;
    this.Flash = false;
    this.Html = false;
    this.ismobile = false;
    this.fullScreen = false;
    this.picPath = "pic/";
    this.UiPath = this.picPath + "3DUI.png";
    this.Zoon_width = 300;
    this.Zoon_height = 300;
    this.Auto = 0;
    this.Interval = 0;
    this.Pause = true;
    this.isShowZoon = false;
    var A = Math.floor(Math.random() * 1000000 + 1);
    this.BODYNAME = "fb" + A;
    this.ZoonDIV = "zn" + A;
    this.ImgID = "id" + A;
    this.ImgNew = "in" + A;
    this.ZimgID = "zd" + A;
    this.ZoonALL = "za" + A;
    this.ImgIDALL = "ia" + A;
    this.ZimgIDALL = "zi" + A;
    this.ZoonLiteBD = "zl" + A;
    this.D3Buttom = "D3" + A;
    this.TitleBar = "tr" + A;
    this.ButtonBar = "br" + A;
    this.displaydiv = "ds" + A;
    this.processdiv = "ps" + A;
    this.resultdiv = "rs" + A;
    this.BigImg = new Image();
    this.LoadedCount = 0;
    this.nowgoingnum = 1;
    this.imageLargeURL = new Array();
    this.imageSmallURL = new Array();
    this.ImagesSmall = new Array();
    this.isdown = false;
    this.tempum = 0;
    this.ZoonALLisShow = false;
    this.ZallBecloseAndgo = 0;
    this.isdownA = false;
    this.STARTID = 0;
    this.newZb = 1;
    this.nowZb = 1;
    this.timerZall = "";
    this.newX = 0;
    this.newY = 0;
    this.NOWX = 0;
    this.NOWY = 0;
    this.MX = 0;
    this.MY = 0;
    this.NX = 0;
    this.NY = 0;
    this.DRX = 0;
    this.DRX2 = 0;
    this.DRX3 = 0;
    this.time = 0;
    $I = function(a) {
        return document.getElementById(a)
    };
    debug = function(c) {
        $I("debugdiv").innerHTML = c
    };
    this.addListener = function(a, e, b) {
        if (a.addEventListener) {
            a.addEventListener(e, b, false)
        } else {
            a.attachEvent("on" + e, b)
        }
    };
    this.getTop = function(e) {
        var a = e.offsetTop;
        if (e.offsetParent != null) {
            a += this.getTop(e.offsetParent)
        };
        return a
    };
    this.getLeft = function(e) {
        var a = e.offsetLeft;
        if (e.offsetParent != null) {
            a += this.getLeft(e.offsetParent)
        };
        return a
    };
    this.getWidth = function(e) {
        return e.clientWidth
    };
    this.getHeight = function(e) {
        return e.clientHeight
    };
    this.getCurrentDirectory = function() {
        var a = location.href;
        var b = a.split("/");
        delete b[b.length - 1];
        var c = b.join("/");
        return c
    };
    this.getJSDirectory = function() {
        var t = document.getElementById('360');
        var s = t.src;
        var a = t;
        var b = a.getAttribute("picurl");
        var d = parseInt(a.getAttribute("picnum"));
        var e = parseInt(a.getAttribute("zoon"));
        var f = a.getAttribute("fullscreen");
        var g = parseInt(a.getAttribute("width"));
        var h = parseInt(a.getAttribute("height"));
        var i = a.getAttribute("show");
        var j = parseInt(a.getAttribute("speed"));
        var k = a.getAttribute("uisrc");
        if (k != undefined) {
            this.UiPath = k
        };
        if (j > 0 && j < 5000) {
            this.autospeed = j
        };
        if (i.indexOf("html") >= 0) {
            this.Html = true
        };
        if (i.indexOf("flash") >= 0) {
            this.Flash = true
        };
        if (e > 0 && e < 10) {
            this.BAY = e
        };
        if (d > 0 && d < 100) {
            this.COUNT = d
        };
        if (f.indexOf("true") >= 0) {
            this.fullScreen = true
        } else {
            if (g > 0 && g < 3000) {
                this.Body_width = g;
                this.Old_width = g
            };
            if (h > 0 && h < 3000) {
                this.Body_height = h;
                this.Old_height = h
            }
        };
        var c = s.split("/");
        delete c[c.length - 1];
        this.JSDirectory = c.join("/");
        if (b.length > 0) {
            this.picPath = b + "/"
        } else {
            this.picPath = this.JSDirectory + "pic/"
        }
    };
    this.LoadSmallImages = function() {
        $I(this.ZimgIDALL).style.display = "none";
        for (i = 1; i <= this.COUNT; i++) {
            this.imageSmallURL[i] = this.picPath + (i) + ".jpg";
            if (this.isShowZoonIMG) {
                this.imageLargeURL[i] = this.picPath + "L" + (i) + ".jpg"
            } else {
                this.imageLargeURL[i] = this.imageSmallURL[i]
            };
            this.ImagesSmall[i] = new Image();
            this.ImagesSmall[i].src = this.imageSmallURL[i];
            this.ImagesSmall[i].Owner = this;
            this.ImagesSmall[i].Loaded = 0;
            this.ImagesSmall[i].ImageID = i;
            this.ImagesSmall[i].onerror = function() {
                this.Loaded = -1;
                this.Owner.LoadBack(this.Loaded, this.ImageID);
                this.onerror = null
            };
            var c = this;
            this.ImagesSmall[i].onload = function() {
                ++c.LoadedCount;
                if (c.LoadedCount == 1) {
                    if (this.width > 0 && this.height > 0) {
                        c.Old_width = this.width;
                        c.Old_height = this.height
                    };
                    c.resetWidth();
                    c.ShowFrim(1)
                };
                c.SETXY_TITLE();
                if (c.LoadedCount >= c.COUNT) {
                    c.Start(1)
                }
            }
        }
    };
    this.LoadBack = function(a, b) {
        ++this.LoadedCount;
        alert(this.ImagesSmall[i].width);
        if (this.LoadedCount == 1) {
            this.resetWidth();
            this.ShowFrim(1)
        };
        this.SETXY_TITLE();
        if (this.LoadedCount >= this.COUNT) {
            this.Start(1)
        }
    };
    this.scrollfunc = function(e) {
        var a = 0;
        var b = this.getEvent(e);
        if (b.wheelDelta) {
            a = b.wheelDelta > 0 ? 1 : -1
        } else if (b.detail) {
            a = b.detail < 0 ? 1 : -1
        };
        var c = $I(this.BODYNAME);
        var d = b.clientX - this.getLeft($I(this.BODYNAME));
        var f = b.clientY - this.getTop($I(this.BODYNAME));
        a = a * this.speed;
        if (this.newZb + a > this.MaxZ) {
            a = this.MaxZ - this.newZb
        };
        var g = this.newZb + a;
        g = g < 1 ? 1 : g;
        g = g > this.MaxZ ? this.MaxZ : g;
        var h = this.Body_width;
        var i = this.Body_height;
        if (g <= this.MaxZ) {
            this.newX -= h * a * (d / h);
            this.newY -= i * a * (f / i)
        };
        this.newZb = g;
        if (this.newX > 0) this.newX = 0;
        if (this.newY > 0) this.newY = 0;
        if (this.newX < -this.Body_width * (g - 1)) {
            this.newX = -this.Body_width * (g - 1)
        };
        if (this.newY < -this.Body_height * (g - 1)) {
            this.newY = -this.Body_height * (g - 1)
        };
        this.ShowZoonALL(true, this.newZb)
    };
    this.refZall = function(t) {
        var b = (t.newZb - t.nowZb) * t.speed + t.nowZb;
        if (b > this.MaxZ + 0.2) b = this.MaxZ + 0.2;
        if (b < 0.8) b = 0.8;
        t.nowZb = b;
        var w = Math.round((t.Body_width * b));
        var h = Math.round((t.Body_height * b));
        var c = $I(t.BODYNAME);
        var d = $I(t.ImgID);
        var e = $I(t.ZoonALL);
        var f = $I(t.ZimgIDALL);
        var n = $I(t.ImgNew);
        var g = t.newY;
        var i = t.newX;
        var x = (i - t.NOWX) * t.speed + t.NOWX;
        var y = (g - t.NOWY) * t.speed + t.NOWY;
        t.NOWX = x;
        t.NOWY = y;
        var a = w;
        var k = a / (this.Old_width / this.Old_height);
        if (k > h) {
            k = h;
            a = k * (this.Old_width / this.Old_height)
        };
        f.style.left = (w - a) / 2 + "px";
        f.style.top = (h - k) / 2 + "px";
        n.style.width = a + "px";
        n.style.height = k + "px";
        n.style.left = (w - a) / 2 + "px";
        n.style.top = (h - k) / 2 + "px";
        d.style.width = w + "px";
        d.style.height = h + "px";
        e.style.width = w + "px";
        e.style.height = h + "px";
        f.style.width = a + "px";
        f.style.height = k + "px";
        if (!t.isdownA) {
            d.style.left = x + "px";
            d.style.top = y + "px";
            e.style.left = x + "px";
            e.style.top = y + "px"
        };
        var j = w - t.Body_width;
        if (j < 0) j = -j;
        if (j < 1 && x < 1 && y < 1) {
            t.ShowZoonALL(false, 1);
            if (t.ZallBecloseAndgo != 0) {
                t.Start(t.ZallBecloseAndgo);
                t.ZallBecloseAndgo = 0
            };
            if (t.ZallBeCloseAndZoon) {
                t.ZallBeCloseAndZoon = false;
                t.ISSHOWZOON(true)
            }
        }
    };
    this.ClickShowZoonALL = function(a, b) {
        this.newZb = b;
        this.newX = -(this.Body_width * this.newZb - this.Body_width) / b;
        this.newY = -(this.Body_height * this.newZb - this.Body_height) / b;
        this.ShowZoonALL(a, b)
    };
    this.ShowZoonALL = function(a, b) {
        this.speed = 0.4;
        if (!a) {
            this.Stop();
            if (this.ZoonALLisShow) {
                this.newZb = 1;
                this.newX = 0;
                this.newY = 0;
                this.ZoonALLisShow = false
            } else {
                this.isBodyWheel = true;
                $I(this.ZoonALL).style.display = "none";
                $I(this.ZimgIDALL).style.display = "none";
                this.ZoonALLisShow = false;
                clearInterval(this.timerZall)
            };
            this.ShowButtonPNG(3, 1)
        } else {
            this.isBodyWheel = false;
            this.ZoonALLisShow = true;
            this.Stop();
            $I(this.ZimgIDALL).style.display = "none";
            var c = this;
            var d = function() {
                document.getElementById(c.ZimgIDALL).style.display = "block"
            };
            this.BigImg.onload = new function() {
                d()
            };
            $I(this.ZimgIDALL).src = this.imageLargeURL[this.nowgoingnum];
            $I(this.ZoonALL).style.display = "block";
            this.newZb = b;
            clearInterval(this.timerZall);
            var e = function() {
                c.refZall(c)
            };
            this.timerZall = window.setInterval(e, 1);
            this.ShowButtonPNG(3, 2)
        }
    };
    this.Reflush = function() {
        var c = this;
        this.newZb = this.newZb < 1 ? 1 : this.newZb;
        this.newZb = this.newZb > this.MaxZ ? this.MaxZ : this.newZb;
        if (this.newX > 0) this.newX = 0;
        if (this.newY > 0) this.newY = 0;
        if (this.newX < -this.Body_width * (this.newZb - 1)) this.newX = -this.Body_width * (this.newZb - 1);
        if (this.newY < -this.Body_height * (this.newZb - 1)) this.newY = -this.Body_height * (this.newZb - 1);
        clearInterval(this.timerZall);
        var e = function() {
            c.refZall(c)
        };
        this.timerZall = window.setInterval(e, 1);
        this.ShowButtonPNG(3, 2)
    };
    this.ShowTouthZoon = function(s) {
        clearInterval(this.timerZall);
        this.ZoonALLisShow = true;
        this.Stop();
        if (s) {
            var c = this;
            var d = function() {
                document.getElementById(c.ZoonALL).style.display = "block"
            };
            $I(this.ZimgIDALL).onload = new function() {
                d()
            };
            $I(this.ZimgIDALL).src = this.imageLargeURL[this.nowgoingnum];
            this.ShowButtonPNG(3, 2)
        };
        var c = $I(this.BODYNAME);
        var z = $I(this.ImgID);
        var e = $I(this.ZoonALL);
        var f = $I(this.ZimgIDALL);
        var n = $I(this.ImgNew);
        var w = this.Body_width * this.newZb;
        var h = this.Body_height * this.newZb;
        z.style.width = w + "px";
        z.style.height = h + "px";
        e.style.width = w + "px";
        e.style.height = h + "px";
        var a = w;
        var b = a / (this.Old_width / this.Old_height);
        if (b > h) {
            b = h;
            a = b * (this.Old_width / this.Old_height)
        };
        z.style.left = this.newX + "px";
        z.style.top = this.newY + "px";
        e.style.left = this.newX + "px";
        e.style.top = this.newY + "px";
        f.style.width = a + "px";
        f.style.height = b + "px";
        n.style.width = a + "px";
        n.style.height = b + "px";
        f.style.left = (w - a) / 2 + "px";
        f.style.top = (h - b) / 2 + "px";
        n.style.left = (w - a) / 2 + "px";
        n.style.top = (h - b) / 2 + "px";
        if (this.newZb == 0.8) {
            this.ZoonALLisShow = false;
            $I(this.ZimgIDALL).style.display = "none"
        }
    };
    this.down = function(o) {
        var e = this.getEvent();
        this.tempum = this.nowgoingnum;
        this.isdown = true;
        if (document.all) {
            this.DRX = e.clientX
        } else {
            this.DRX = e.pageX - o.offsetLeft
        };
        this.time = (new Date()).getTime();
        if (document.all) o.setCapture();
        return false
    };
    this.up = function(o, e) {
        var a = this.getEvent();
        this.isdown = false;
        if (document.all) {
            this.DRX3 = a.offsetX
        } else {
            this.DRX3 = a.pageX - o.offsetLeft
        };
        if (this.Auto != 0) {
            var d = (new Date()).getTime() - this.time;
            if (d < 500) this.Start(this.Auto);
            this.Auto = 0
        };
        if (document.all) o.releaseCapture()
    };
    this.getEvent = function() {
        if (document.all) return window.event;
        func = this.getEvent.caller;
        while (func != null) {
            var a = func.arguments[0];
            if (a) {
                if ((a.constructor == Event || a.constructor == MouseEvent) || (typeof(a) == "object" && a.preventDefault && a.stopPropagation)) {
                    return a
                }
            };
            func = func.caller
        };
        return null
    };
    this.move = function(o) {
        var e = this.getEvent();
        var a = 0;
        var b = this.tempum;
        if (document.all) {
            this.DRX2 = e.clientX
        } else {
            this.DRX2 = e.pageX - o.offsetLeft
        };
        if (this.isdown) {
            var c = Math.round((this.DRX2 - this.DRX) / 10);
            if (Math.abs(c) > 10) {
                this.Auto = c < 0 ? -1 : 1
            } else {
                this.Auto = 0
            };
            var d = this.tempum + c;
            d = d % this.COUNT;
            while (d < 1) {
                d += this.COUNT
            };
            this.nowgoingnum = d;
            this.Pause = true;
            this.ShowFrim(this.nowgoingnum)
        } else {
            this.ShowZoonDB()
        }
    };
    this.downA = function(o) {
        if (document.all) o.setCapture();
        var e = this.getEvent();
        var a = this.tempum;
        this.isdownA = true;
        this.MX = e.clientX;
        this.MY = e.clientY;
        this.SX = this.newX;
        this.SY = this.newY;
        return false
    };
    this.upA = function(o) {
        var e = this.getEvent();
        if (this.downA) {
            if (document.all) o.releaseCapture();
            var a = $I(this.BODYNAME);
            var b = $I(this.ImgID);
            this.NOWX = this.newX;
            this.NOWY = this.newY;
            this.newX += (e.clientX - this.MX) * 0.2;
            this.newY += (e.clientY - this.MY) * 0.2;
            if (this.newX > 0) this.newX = 0;
            if (this.newY > 0) this.newY = 0;
            if (this.newX < -this.Body_width * (this.newZb - 1)) this.newX = -this.Body_width * (this.newZb - 1);
            if (this.newY < -this.Body_height * (this.newZb - 1)) this.newY = -this.Body_height * (this.newZb - 1);
            this.ShowZoonALL(true, this.newZb)
        };
        this.isdownA = false
    };
    this.moveA = function(o) {
        var e = this.getEvent();
        if (this.isdownA) {
            this.newX = this.SX + (e.clientX - this.MX);
            this.newY = this.SY + (e.clientY - this.MY);
            var c = $I(this.ImgID);
            var d = $I(this.ZoonALL);
            c.style.left = this.newX + "px";
            c.style.top = this.newY + "px";
            d.style.left = this.newX + "px";
            d.style.top = this.newY + "px"
        }
    };
    this.dbclick = function() {
        this.isShowZoon = true;
        this.ShowZoonDB()
    };
    this.dbclickzoon = function() {
        this.isShowZoon = false;
        this.HideZoon()
    };
    this.setTIMEout = function() {
        if (this.DRX2 > this.DRX3) {
            this.Start(1)
        };
        if (this.DRX2 < this.DRX3) {
            this.Start(-1)
        }
    };
    this._TITLE = function() {
        var a = this.ismobile ? 220 - 37 : 220;
        var b = (this.Body_width - a) / 2;
        var c = a + b;
        var d = '<style>';
        d += '#' + this.D3Buttom + '1, #' + this.D3Buttom + '2, #' + this.D3Buttom + '3, #' + this.D3Buttom + '4,#' + this.D3Buttom + '5 {width:37px;height:30px;position:relative;background:url(' + this.UiPath + ') no-repeat;}';
        d += '#' + this.D3Buttom + 's {LIST-STYLE-TYPE: none; HEIGHT: 30px;}';
        d += '#' + this.D3Buttom + 's LI {PADDING-BOTTOM: 0px; MARGIN: 0px 0px 0px 5px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FLOAT: left; PADDING-TOP: 0px}';
        d += '#' + this.D3Buttom + 's LI A {BACKGROUND-IMAGE: url(' + this.UiPath + '); TEXT-INDENT: -99999px; OUTLINE-STYLE: none; DISPLAY: block;HEIGHT: 30px;OVERFLOW: hidden; blr:expression(this.onFocus=this.blur());outline:none;}';
        d += '#' + this.D3Buttom + 's LI A:focus {-moz-outline-style: none;}';
        d += '#' + this.D3Buttom + '0 {WIDTH: 10px;}';
        d += '#' + this.D3Buttom + '1 {WIDTH: 37px; BACKGROUND-POSITION: 0px 0px}';
        d += '#' + this.D3Buttom + '2 {WIDTH: 37px; BACKGROUND-POSITION: -37px 0px}';
        d += '#' + this.D3Buttom + '3 {WIDTH: 37px; BACKGROUND-POSITION: -74px 0px}';
        d += '#' + this.D3Buttom + '4 {WIDTH: 37px; BACKGROUND-POSITION: -111px 0px}';
        d += '#' + this.D3Buttom + '5 {WIDTH: 37px; BACKGROUND-POSITION: -148px 0px}';
        d += '</style>';
        d += '<div id="' + this.ButtonBar + '" style="position:absolute; height: 33px;line-height: 33px; text-align: center; z-index:90000;">';
        d += '<div id=' + this.D3Buttom + 's><li><div style="width:' + b + 'px;" id="' + this.TitleBar + 'Left">&nbsp;</div></li>';
        d += '<li><a id=' + this.D3Buttom + '1 href="javascript:void(0);" ></a></li><li><a id=' + this.D3Buttom + '2 href="javascript:void(0);"></a></li>';
        d += '<li><a id=' + this.D3Buttom + '3 href="javascript:void(0);"></a></li><li><a id=' + this.D3Buttom + '4 href="javascript:void(0);;"></a></li>';
        d += this.ismobile ? '' : '<li><a id=' + this.D3Buttom + '5 href="javascript:void(0);"></a></li>';
        d += '</div></div>';
        d += '<div id="' + this.TitleBar + '"  style="display:display;position:absolute;z-index: 100;" ><span id="' + this.displaydiv + '"></span>&nbsp;<span id="' + this.processdiv + '"></span>&nbsp;<span id="' + this.resultdiv + '"></span></div>';
        return d
    };
    var v = this;
    this.isBodyWheel = true;
    var z = function() {
        if (!v.isBodyWheel) {
            try {
                var a = v.getEvent();
                a.preventDefault();
                return false
            } catch (e) {}
        } else {
            return true
        }
    };
    this.StopBodywheel = function() {
        if (document.addEventListener) {
           // document.addEventListener('DOMMouseScroll', z, false)
        } else {
            //document.body.onmousewheel = z
        }
    };
    this.resetWidth = function() {
        var c = $I(this.BODYNAME);
        var n = $I(this.ImgNew);
        var d = $I(this.ImgID);
        var a = $I(this.ZimgIDALL);
        var f = $I(this.TitleBar + 'Left');
        var b = $I(this.ButtonBar + 'touch');
        if (this.fullScreen) {
            this.Body_width = this.getWidth(c.parentNode);
            this.Body_height = this.getHeight(c.parentNode);
            if (this.Body_width < 100 || this.Body_height < 100) {
                this.Body_width = (document.documentElement.clientWidth == 0) ? document.body.clientWidth : document.documentElement.clientWidth;
                this.Body_height = (document.documentElement.clientHeight == 0) ? document.body.clientHeight : document.documentElement.clientHeight
            }
        };
        c.style.width = this.Body_width + "px";
        c.style.height = this.Body_height + "px";
        d.style.width = this.Body_width + "px";
        d.style.height = this.Body_height + "px";
        if (this.ImagesSmall[0]) {
            var e = this.ImagesSmall[0].width;
            var g = this.ImagesSmall[0].height
        };
        if (e > 0 && g > 0) {
            this.Old_width = e;
            this.Old_height = g
        };
        var w = this.Body_width;
        var h = w / (this.Old_width / this.Old_height);
        if (h > this.Body_height) {
            h = this.Body_height;
            w = h * (this.Old_width / this.Old_height)
        };
        b.style.height = (this.Body_height - 33) + "px";
        $I(this.ButtonBar).style.top = (this.Body_height - 33) + "px";
        n.style.width = w + "px";
        n.style.height = h + "px";
        n.style.left = (this.Body_width - w) / 2 + "px";
        n.style.top = (this.Body_height - h) / 2 + "px";
        a.style.width = w + "px";
        a.style.height = h + "px";
        var i = (this.Body_width - 220) / 2;
        f.style.width = i + "px"
    };
    this.CreateZoonDiv = function() {
        var b = this.getLeft($I(this.BODYNAME)) + this.getWidth($I(this.BODYNAME));
        var c = this.getTop($I(this.BODYNAME));
        var a = $I(this.ZoonLiteBD);
        var d = this;
        this.addListener(a, "mousemove", function() {
            d.ShowZoon()
        });
        this.addListener(a, "mouseout", function() {
            d.HideZoon()
        });
        this.addListener(a, "click", function() {
            d.dbclickzoon()
        })
    };
    this._ZoonDiv = function() {
        return ('<div id=' + this.ZoonLiteBD + ' style="display:none;overflow:hidden;position:absolute;border:2px solid #eeeeee;z-index:102"><div id="' + this.ZimgID + 'BGDIV" style="left: 0px;top: 0px;z-index:103;position:absolute;"><img  id="' + this.ZimgID + 'BG" src="" onmousedown="return false;"style="position:absolute;overflow:hidden;" /></div><div id="' + this.ZimgID + 'DIV" style="left: 0px;top: 0px;z-index:103;position:absolute;"><img  id="' + this.ZimgID + '" src="" onmousedown="return false;" style="position:absolute;overflow:hidden;" /></div></div>')
    };
    this.CreateBODYDiv = function() {
        var a = this;
        var b = '<div id=' + this.BODYNAME + ' style="overflow:hidden;position:relative;">';
        b += '<div id=' + this.ZoonALL + ' style="text-align: center; vertical-align:middle; z-index:300;position:absolute;overflow:hidden;"><img id="' + this.ZimgIDALL + '" width="100" height="100" src=""  style="position:absolute;overflow:hidden;" /></div>';
        b += '<div id="' + this.ButtonBar + 'touch" style="position:absolute; width:100%;height:95%; z-index:200; ;overflow:hidden;"></div>';
        b += '<div id="' + this.ImgID + '" style="text-align: center; vertical-align:middle; z-index:100;position:absolute;overflow:hidden;"><img id="' + this.ImgNew + '" src="" style="position:absolute;overflow:hidden;" /></div>';
        b += this._TITLE();
        b += this._ZoonDiv();
        b += '</div>';
        var costam = document.createElement("div");
        costam.innerHTML=b;
        document.getElementById("item_360").appendChild(costam);
        this.resetWidth();
        var c = $I(this.BODYNAME);
        c.onselectstart = function() {
            return false
        };
        var d = $I(this.ImgID);
        var g = $I(this.ButtonBar + 'touch');
        var e = $I(this.ZoonALL);
        this.StopBodywheel();
        var f = function() {
            a.isBodyWheel = false;
            a.scrollfunc(d);
            return false
        };
        g.onmousemove = function() {
            a.isBodyWheel = false;
            a.move(d)
        };
        g.onmousedown = function() {
            a.Stop();
            a.down(d);
            return false
        };
        g.onmouseup = function() {
            a.up(d)
        };
        g.onmouseout = function() {
            a.isBodyWheel = true
        };
        g.ondblclick = function() {
            a.dbclick(d)
        };
        //g.onmousewheel = f;
        //this.addListener(d, "DOMMouseScroll", f);
        e.onselectstart = function() {
            return false
        };
        e.onselectstart = function() {
            return false
        };
        e.onmousemove = function() {
            a.isBodyWheel = false;
            a.moveA(e)
        };
        e.onmousedown = function() {
            a.Stop();
            a.downA(e);
            if (!document.all) return false
        };
        e.onmouseup = function() {
            if (a.isdownA) a.upA(e)
        };
        e.onmouseout = function() {
            if (a.isdownA) a.upA(e)
        };
        e.ondblclick = function() {
            return false
        };
        //e.onmousewheel = f;
        //this.addListener(e, "DOMMouseScroll", f);
        e.onmouseout = function(b) {
            a.isBodyWheel = true;
            b.releaseCapture();
            if (a.isdownA) a.upA(e)
        };
        $I(a.D3Buttom + "1").onmousemove = function() {
            window.status = ''
        };
        $I(a.D3Buttom + "2").onmousemove = function() {
            window.status = ''
        };
        $I(a.D3Buttom + "3").onmousemove = function() {
            window.status = ''
        };
        $I(a.D3Buttom + "4").onmousemove = function() {
            window.status = ''
        };
        if (!this.ismobile) {
            $I(a.D3Buttom + "5").onmousemove = function() {
                a.move(d, window.event);
                window.status = ''
            }
        }
        $I(a.D3Buttom + "1").onmousedown = function() {
            return false
        };
        $I(a.D3Buttom + "2").onmousedown = function() {
            return false
        };
        $I(a.D3Buttom + "3").onmousedown = function() {
            return false
        };
        $I(a.D3Buttom + "4").onmousedown = function() {
            return false
        };
        if (!this.ismobile) {
            $I(a.D3Buttom + "5").onmousedown = function() {
                return false
            }
        }
        $I(a.D3Buttom + "1").onclick = function() {
            a.D3Button2(-1)
        };
        $I(a.D3Buttom + "2").onclick = function() {
            a.D3Button2(1)
        };
        $I(a.D3Buttom + "3").onclick = function() {
            a.ClickShowZoonALL(!a.ZoonALLisShow, 2)
        };
        $I(a.D3Buttom + "4").onclick = function() {
            if (a.Pause) {
                a.D3Button2(1)
            } else {
                a.ShowZoonALL(false, 1);
                a.Stop()
            }
        };
        if (!this.ismobile) {
            $I(a.D3Buttom + "5").onclick = function() {
                a.Stop();
                if (a.ZoonALLisShow) {
                    a.ZallBeCloseAndZoon = true;
                    a.ShowZoonALL(false, 1)
                };
                if (a.isShowZoon) {
                    a.HideZoon()
                } else {
                    a.ISSHOWZOON(true);
                    a.move(d, window.event)
                }
            }
        }
    };
    this.ShowButtonPNG = function(o, t) {
        $I(this.D3Buttom + "" + o).style.backgroundPosition = "-" + ((o - 1) * 37) + "px -" + ((t - 1) * 30) + "px"
    };
    this.D3Button2 = function(a) {
        if (this.ZoonALLisShow) {
            this.ZallBecloseAndgo = a;
            this.ShowZoonALL(false, 1)
        } else {};
        if (this.Pause) this.Start(a);
        else {
            if (this.STARTID != a) {
                this.Stop();
                this.Start(a)
            }
        }
    };
    this.ShowZoonDB = function(a) {
        if (this.Pause && this.isShowZoon) {
            this.INITDB();
            this.ISSHOWZOON(true);
            $I(this.ZimgID).style.display = "none";
            $I(this.ZimgID + 'BG').src = this.imageSmallURL[this.nowgoingnum];
            var w = this.Body_width * this.BAY;
            var h = this.Body_height * this.BAY;
            $I(this.ZimgID + 'BGDIV').style.width = w + "px";
            $I(this.ZimgID + 'BGDIV').style.height = h + "px";
            $I(this.ZimgID + 'DIV').style.width = w + "px";
            $I(this.ZimgID + 'DIV').style.height = h + "px";
            var d = w;
            var e = d / (this.Old_width / this.Old_height);
            if (e > h) {
                e = h;
                d = e * (this.Old_width / this.Old_height)
            };
            $I(this.ZimgID + 'BG').style.width = d + "px";
            $I(this.ZimgID + 'BG').style.height = e + "px";
            $I(this.ZimgID).style.width = d + "px";
            $I(this.ZimgID).style.height = e + "px";
            $I(this.ZimgID + 'BG').style.left = (w - d) / 2 + "px";
            $I(this.ZimgID + 'BG').style.top = (h - e) / 2 + "px";
            $I(this.ZimgID).style.left = (w - d) / 2 + "px";
            $I(this.ZimgID).style.top = (h - e) / 2 + "px";
            var b = this;
            var c = function() {
                document.getElementById(b.ZimgID).style.display = "block"
            };
            if (this.isShowZoonIMG) {
                $I(this.ZimgID).onload = new function() {
                    c()
                };
                $I(this.ZimgID).src = this.imageLargeURL[this.nowgoingnum]
            } else {
                $I(this.ZimgID).style.display = "none"
            };
            this.ShowZoon(1)
        }
    };
    this.ShowZoon = function(a) {
        this.INITDB();
        var b = this.getLeft($I(this.ImgID));
        var c = this.getTop($I(this.ImgID));
        var d = this.getLeft($I(this.ZoonLiteBD));
        var e = this.getTop($I(this.ZoonLiteBD));
        var x = d - b;
        var y = e - c;
        $I(this.BODYNAME).style.width = this.getWidth($I(this.ImgID)) + "px";
        $I(this.BODYNAME).style.height = this.getHeight($I(this.ImgID)) + "px";
        var f = this.getWidth($I(this.ZoonLiteBD)) + 4;
        var g = this.getHeight($I(this.ZoonLiteBD)) + 4;
        $I(this.ZoonLiteBD).scrollLeft = this.Zoonx * this.BAY - f / 2;
        $I(this.ZoonLiteBD).scrollTop = this.Zoony * this.BAY - g / 2
    };
    this.ISSHOWZOON = function(a) {
        if (a) {
            this.ShowButtonPNG(5, 2)
        } else {
            this.ShowButtonPNG(5, 1)
        };
        this.isShowZoon = a
    };
    this.Zoonx = 0;
    this.Zoony = 0;
    this.INITDB = function() {
        var a = 0;
        var b = 0;
        var c = this.getEvent();
        a = document.body.scrollTop;
        b = document.body.scrollLeft;
        if (!a) {
            a = document.documentElement.scrollTop
        };
        if (!a) {
            a = 0
        };
        if (!b) {
            b = document.documentElement.scrollLeft
        };
        if (!b) {
            b = 0
        };
        $I(this.ZoonLiteBD).style.display = "block";
        var d = 0;
        var e = 0;
        var w = this.getWidth($I(this.ImgID));
        var h = this.getHeight($I(this.ImgID));
        $I(this.ZoonLiteBD).style.width = this.Zoon_width + "px";
        $I(this.ZoonLiteBD).style.height = this.Zoon_height + "px";
        var f = this.getWidth($I(this.ZoonLiteBD)) + 4;
        var g = this.getHeight($I(this.ZoonLiteBD)) + 4;
        var i = c.clientX + b - this.getLeft($I(this.BODYNAME)) - f / 2;
        var j = c.clientY + a - this.getTop($I(this.BODYNAME)) - g / 2;
        this.Zoonx = c.clientX + b - this.getLeft($I(this.BODYNAME));
        this.Zoony = c.clientY + a - this.getTop($I(this.BODYNAME));
        i = i < d ? d : i;
        i = i > d + w - f ? d + w - f : i;
        j = j < e ? e : j;
        j = j > e + h - g ? e + h - g : j;
        $I(this.ZoonLiteBD).style.top = (j) + "px";
        $I(this.ZoonLiteBD).style.left = (i) + "px"
    };
    this.HideZoon = function() {
        this.ISSHOWZOON(false);
        $I(this.ZimgID).style.display = "none";
        $I(this.ZoonLiteBD).style.display = "none"
    };
    this.ShowFrim = function(N) {
        N = typeof(N) == "undefined" ? 1 : N;
        N = N > this.COUNT ? this.COUNT : N;
        N = N < 1 ? 1 : N;
        $I(this.ImgNew).src = this.ImagesSmall[N].src;
        $I(this.ImgID).style.display = "block";
        this.nowgoingnum = N
    };
    this.SETXY_TITLE = function() {
        var h = this.getHeight($I(this.BODYNAME));
        $I(this.ButtonBar).style.top = "10px";
        $I(this.ButtonBar).style.top = (h - this.getHeight($I(this.ButtonBar))) + "px"
    };
    this.Start = function(N) {
        this.STARTID = N;
        this.Stop();
        this.ShowButtonPNG(4, 1);
        var a = this;
        var b = function() {
            a.DOTurnLeft(a)
        };
        var c = function() {
            a.DOTurnRight(a)
        };
        if (N == 1) {
            this.Interval = window.setInterval(b, this.autospeed)
        } else {
            if (this.nowgoingnum == 1) {
                this.nowgoingnum = this.COUNT
            };
            this.Interval = window.setInterval(c, this.autospeed)
        }
    };
    this.Stop = function() {
        this.ShowButtonPNG(4, 2);
        this.STARTID = 0;
        this.Pause = true;
        clearInterval(this.Interval)
    };
    this.DOTurnLeft = function(t) {
        t.Pause = false;
        if (!t.Go(1)) {
            t.Pause = true;
            t.Stop()
        }
    };
    this.DOTurnL = function(t) {
        t.Pause = false;
        if (!t.Go(1)) {
            t.Pause = true;
            t.Stop()
        }
    };
    this.DOTurnRight = function(t) {
        t.Pause = false;
        if (!t.Go(-1)) {
            t.Pause = true;
            t.TurnLeft = false;
            t.Stop()
        }
    };
    this.Go = function(n) {
        var t = this;
        if (t.Pause) {
            return false
        };
        var a = t.nowgoingnum + n;
        if (a > t.COUNT) {
            a = 1;
            t.nowgoingnum = a;
            t.ShowFrim(t.nowgoingnum)
        };
        if (a < 1) {
            a = t.COUNT;
            t.nowgoingnum = a;
            t.ShowFrim(t.nowgoingnum)
        };
        t.nowgoingnum = a;
        t.ShowFrim(t.nowgoingnum);
        return true
    };
    this.Go2 = function(n) {
        if (this.Pause) {
            return false
        };
        var a = this.nowgoingnum + n;
        if (a > this.COUNT) {
            a = 1
        };
        if (a < 1) {
            a = this.COUNT
        };
        this.nowgoingnum = a;
        this.ShowFrim(this.nowgoingnum);
        return true
    };
    this.chkFlash = function() {
        var a = false;
        if (document.all) {
            if (new ActiveXObject('ShockwaveFlash.ShockwaveFlash')) {
                a = true
            }
        } else {
            if (navigator.plugins && navigator.plugins.length > 0) {
                if (navigator.plugins["Shockwave Flash"]) {
                    a = true
                }
            }
        };
        return a
    };
    this.ShowFlash = function() {
        var a = this.JSDirectory + "index.swf";
        var w, h;
        if (this.fullScreen) {
            w = "100%";
            h = "100%"
        } else {
            w = this.Body_width;
            h = this.Body_height
        };
        var s = '<object type="application/x-shockwave-flash" data="' + a + '" width="' + w + '" height="' + h + '"><param name="movie" value="' + a + '" /><param name="quality" value="high" /><param name="allowFullScreen" value="true" /></object>';
        document.write(s)
    };
    this.WKTouch = {
        node: 0,
        parent: 0,
        zIndexCount: 1,
        handleEvent: function(e) {
            switch (e.type) {
                case 'touchstart':
                    this.onTouchStart(e);
                    break;
                case 'touchmove':
                    this.onTouchMove(e);
                    break;
                case 'touchend':
                    this.onTouchEnd(e);
                    break;
                case 'touchcancel':
                    this.onTouchCancel(e);
                    break
            }
        },
        init: function(t, a) {
            this.node = $I(a);
            this.parent = t;
            this.startX = 0;
            this.startY = 0;
            this.P1P = 0;
            this.P2P = 0;
            this.Z = 0;
            this.Cenx = 0;
            this.Ceny = 0;
            this.Nx = 0;
            this.Ny = 0;
            this.Now = 0;
            this.Auto = 0;
            this.time = 0;
            this.movedX = 0;
            this.movedY = 0;
            this.node.addEventListener('touchstart', this, false);
            this.node.addEventListener('touchmove', this, false);
            this.node.addEventListener('touchend', this, false);
            this.node.addEventListener('touchcancel', this, false)
        },
        onTouchStart: function(e) {
            this.parent.Stop();
            e.preventDefault();
            this.Nx = this.parent.newX;
            this.Ny = this.parent.newY;
            this.movedX = 0;
            this.movedY = 0;
            this.Now = this.parent.nowgoingnum;
            this.time = (new Date()).getTime();
            if (e.targetTouches.length == 1) {
                this.startX = e.targetTouches[0].clientX;
                this.startY = e.targetTouches[0].clientY
            } else if (e.targetTouches.length == 2) {
                var a = e.targetTouches[0].clientX;
                var b = e.targetTouches[0].clientY;
                var c = e.targetTouches[1].clientX;
                var d = e.targetTouches[1].clientY;
                var f = Math.abs(c - a);
                var g = Math.abs(d - b);
                this.P1P = Math.pow((f * f + g * g), 0.5);
                a = a < c ? a : c;
                b = b < d ? b : d;
                var h = a + f / 2 - this.node.offsetLeft - this.parent.newX;
                var i = b + g / 2 - this.node.offsetTop - this.parent.newY;
                this.Cenx = h / (this.parent.Body_width * this.parent.newZb);
                this.Ceny = i / (this.parent.Body_height * this.parent.newZb);
                this.Z = this.parent.newZb
            }
        },
        onTouchMove: function(e) {
            e.preventDefault();
            var a = this.parent.Body_width / 100;
            var b = e.targetTouches[0].clientX;
            var d = e.targetTouches[0].clientY;
            if (e.targetTouches.length == 1) {
                if (!this.parent.ZoonALLisShow) {
                    var f = b - this.startX;
                    var h = Math.round(f / a);
                    if (Math.abs(h) > 20) {
                        this.Auto = h < 0 ? -1 : 1
                    } else {
                        this.Auto = 0
                    };
                    var i = this.Now + h;
                    i = i % this.parent.COUNT;
                    while (i < 1) {
                        i += this.parent.COUNT
                    };
                    this.parent.nowgoingnum = i;
                    this.parent.ShowFrim(this.parent.nowgoingnum)
                } else {
                    this.movedX = b - this.startX;
                    this.movedY = d - this.startY;
                    this.parent.newX = this.parent.NOWX = this.Nx + this.movedX;
                    this.parent.newY = this.parent.NOWY = this.Ny + this.movedY;
                    this.parent.ShowTouthZoon(false)
                }
            } else if (e.targetTouches.length == 2) {
                var j = e.targetTouches[1].clientX;
                var k = e.targetTouches[1].clientY;
                var l = Math.abs(j - b);
                var m = Math.abs(k - d);
                this.P2P = Math.pow((l * l + m * m), 0.5);
                var c = $I(this.parent.BODYNAME);
                var a = (this.P2P - this.P1P) / (this.parent.Body_width / 2.5);
                var g = this.Z + a;
                g = g <= 0.8 ? 0.8 : g;
                g = g >= this.parent.MaxZ ? this.parent.MaxZ : g;
                if (g == this.parent.MaxZ) a = g - this.Z;
                var n = this.parent.Body_width;
                var o = this.parent.Body_height;
                var x = this.Nx - n * a * this.Cenx;
                var y = this.Ny - o * a * this.Ceny;
                x = x > n * 0.1 ? x = n * 0.1 : x;
                y = y > o * 0.1 ? y = o * 0.1 : y;
                x = x < -n * (g - 0.9) ? -n * (g - 0.9) : x;
                y = y < -o * (g - 0.9) ? -o * (g - 0.9) : y;
                this.parent.newX = this.parent.NOWX = x;
                this.parent.newY = this.parent.NOWY = y;
                this.parent.nowZb = g;
                this.parent.newZb = g;
                this.parent.ShowTouthZoon(true)
            }
        },
        onTouchEnd: function(e) {
            if (this.Auto != 0) {
                var d = (new Date()).getTime() - this.time;
                if (d < 500) this.parent.Start(this.Auto);
                this.Auto = 0
            } else if (e.targetTouches.length == 0) {
                this.parent.newX += this.movedX * 0.2;
                this.parent.newY += this.movedY * 0.2;
                this.parent.speed = 0.08;
                this.parent.Reflush()
            };
            this.Nx = this.parent.newX;
            this.Ny = this.parent.newY;
            if (this.parent.newZb > 0) {
                $I(this.parent.ButtonBar + 'touch').style.zIndex = 400
            } else {
                $I(this.parent.ButtonBar + 'touch').style.zIndex = 200
            };
            this.startX = e.targetTouches[0].clientX;
            this.startY = e.targetTouches[0].clientY
        },
        onTouchCancel: function(e) {}
    };
    var B = this;
    var C = function() {
        $I(B.BODYNAME).style.width = "1px";
        $I(B.BODYNAME).style.height = "1px";
        B.resetWidth()
    };
    this.getJSDirectory();
    if ((this.chkFlash() && !this.Html) || this.Flash) {
        this.ShowFlash()
    } else {
        this.ismobile = (navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/Android/i)) || (navigator.userAgent.match(/iPad/i));
        this.CreateBODYDiv();
        this.CreateZoonDiv();
        this.LoadSmallImages();
        this.ShowFrim(1);
        this.WKTouch.init(this, this.ButtonBar + 'touch')
    };
    addEventListener('load', function() {
        C();
        addEventListener("onorientationchange" in window ? "orientationchange" : "resize", C, false)
    })
}());