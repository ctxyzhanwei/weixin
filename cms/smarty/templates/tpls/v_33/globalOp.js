/* 作者：孟龙
功能：实现顶部选项卡功能
时间: 2012年8月25日11:16:44
*/
function GetTab(sender, content, current, classname, tabname) {
    var parentUl = $(sender).up("ul");
    var oldIndex = parentUl.childElements().indexOf(parentUl.down(current));
    var nowIndex = parentUl.childElements().indexOf($(sender));
    parentUl.down(current).removeClassName(classname);
    $(sender).addClassName(classname);
    content.down(tabname, oldIndex).hide();
    if (content.down(tabname, nowIndex).innerHTML == "") {
        var myAjax = new Ajax.Request(
                "GlobalTemplateOption/Skin" + nowIndex + ".htm",
                {
                    method: "get",
                    onComplete: function (transport) {
                        content.down(tabname, nowIndex).innerHTML = transport.responseText;
                    }, asynchronous: false
                }
             )
    }
    content.down(tabname, nowIndex).show();
}

/* 作者：孟龙
功能：实现上传更新、图片，所用插件 swfUpload.js
时间: 2012年9月6日14:43:57
参数说明：
id：上传控件替换的部分；
pid：父级层id；
enterid：触发click事件的‘按钮’id；
filenameid：显示要上传图片名称层id；
accpetid：接收ajax回传参数的隐藏input id；
idOrNameStr & colName：为setWin.js中 colSave方法参数；
*/
function upLoadImg(id, pid, filenameid, accpetid, controlname) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        fileQueued: function (file) {
            $(filenameid).innerHTML = file.name;
            $(filenameid).show();
        },
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                if (controlname == "Col_Banner") {
                    $("uploadT").innerHTML = "上传成功！";
                } else if (controlname == "Col_Logo") {
                    $("logoF").innerHTML = "上传成功！";
                }
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            if (controlname == "Col_Banner") {
                $("uploadT").innerHTML = percent + "%";
            } else if (controlname == "Col_Logo") {
                $("logoF").innerHTML = percent + "%";
            }
        }
    });
}

/* banner第一张图片上传按钮初始化 */

function uploadBanner1(id, filenameid, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        fileQueued: function (file) {
            $(filenameid).innerHTML = file.name;
            $(filenameid).show();
        },
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("fileTypeOne").innerHTML = "上传成功";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("fileTypeOne").innerHTML = percent + "%";
        }
    });
}

/* banner第二张图片上传按钮初始化 */

function uploadBanner2(id, filenameid, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        fileQueued: function (file) {
            $(filenameid).innerHTML = file.name;
            $(filenameid).show();
        },
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("fileTypeTwo").innerHTML = "上传成功";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("fileTypeTwo").innerHTML = percent + "%";
        }
    });
}

/* banner第三张图片上传按钮初始化 */

function uploadBanner3(id, filenameid, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        fileQueued: function (file) {
            $(filenameid).innerHTML = file.name;
            $(filenameid).show();
        },
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("fileTypeThree").innerHTML = "上传成功";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("fileTypeThree").innerHTML = percent + "%";
        }
    });
}

/* app第一张图片上传 */
function uploadAppOne(id, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        //fileQueued: function (file) {
        //    $(filenameid).innerHTML = file.name;
        //    $(filenameid).show();
        //},
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("AppOneImg").innerHTML = "上传成功！";
                $("app1").innerHTML = "<a href=\"###\" onclick=\"returnAppMode('Col_AppMode',this)\">一键还原</a>";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("AppOneImg").innerHTML = percent + "%";
        }
    });
}

/* app第二张图片上传 */
function uploadAppTwo(id, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        //fileQueued: function (file) {
        //    $(filenameid).innerHTML = file.name;
        //    $(filenameid).show();
        //},
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("AppTwoImg").innerHTML = "上传成功！";
                $("app2").innerHTML = "<a href=\"###\" onclick=\"returnAppMode('Col_AppMode',this)\">一键还原</a>";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("AppTwoImg").innerHTML = percent + "%";
        }
    });
}

/* app第三张图片上传 */
function uploadAppThree(id, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        //fileQueued: function (file) {
        //    $(filenameid).innerHTML = file.name;
        //    $(filenameid).show();
        //},
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("AppThreeImg").innerHTML = "上传成功！";
                $("app3").innerHTML = "<a href=\"###\" onclick=\"returnAppMode('Col_AppMode',this)\">一键还原</a>";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("AppThreeImg").innerHTML = percent + "%";
        }
    });
}

/* app第四张图片上传 */
function uploadAppFour(id, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        //fileQueued: function (file) {
        //    $(filenameid).innerHTML = file.name;
        //    $(filenameid).show();
        //},
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("AppFourImg").innerHTML = "上传成功！";
                $("app4").innerHTML = "<a href=\"###\" onclick=\"returnAppMode('Col_AppMode',this)\">一键还原</a>";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("AppFourImg").innerHTML = percent + "%";
        }
    });
}

/* app第五张图片上传 */
function uploadAppFive(id, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        //fileQueued: function (file) {
        //    $(filenameid).innerHTML = file.name;
        //    $(filenameid).show();
        //},
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("AppFiveImg").innerHTML = "上传成功！";
                $("app5").innerHTML = "<a href=\"###\" onclick=\"returnAppMode('Col_AppMode',this)\">一键还原</a>";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("AppFiveImg").innerHTML = percent + "%";
        }
    });
}

/* app第六张图片上传 */
function uploadAppSix(id, accpetid) {
    qhwUpload.displaySWF();

    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/UpFile",
        placeHoldId: id,
        fileType: "*.jpeg;*.jpg;*.png;*.gif;",

        fileDialogComplete: function (numFilesSelected, numFilesQueued, numFilesInQueue) {
            this.startUpload();
        },
        //fileQueued: function (file) {
        //    $(filenameid).innerHTML = file.name;
        //    $(filenameid).show();
        //},
        uploadSuccess: function (file, serverData) {
            var obj = serverData.evalJSON();
            if (obj.return_code == 0) {
                $(accpetid).value = obj.img_name;
                $("AppSixImg").innerHTML = "上传成功！";
                $("app6").innerHTML = "<a href=\"###\" onclick=\"returnAppMode('Col_AppMode',this)\">一键还原</a>";
            }
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $("AppSixImg").innerHTML = percent + "%";
        }
    });
}

/* 作者：孟龙
功能：根据控件名称，生成相应的html
时间: 2012年9月10日9:52:00 */

function CreateWinControl(_Wincolname, _elementid) {
    $(_elementid).setAttribute("style", "text-align: center;");
    $(_elementid).innerHTML = "<img src='ajaxloading.gif'/*tpa=http://ui.tiantis.com/Images/MShopDec/ajaxloading.gif*/ />";
    var url = "/AJAXpage/CreateWinControl";
    var _Winpars = "_colname=" + _Wincolname;
    var myAjax = new Ajax.Request(url, { method: "get",
        parameters: _Winpars,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            $(_elementid).setAttribute("style", "text-align: inherit;");
            $(_elementid).innerHTML = req.responseText;
        }, asynchronous: false
    });
}

/* 作者：孟龙
功能：头部导航设置效果替换
时间: 2012年9月13日18:21:11 */

function controlChange(sender, isUpdate, _controlname) {
    var currentTR = $(sender).up("tr");
    var nearTR = currentTR.next();
    if (!nearTR) {
        nearTR = currentTR.previous();
    }
    $(currentTR, nearTR).invoke("toggle");
    if (isUpdate) {
        var controlName = $(sender).up("tr").down("input").getValue();
        var sortId = $(sender).up("tr").down("input", 1).getValue();
        updateNavigation(controlName, sortId, "", "", _controlname, function () {
            //            nearTR.down("span").innerHTML = controlName;
            $("menu").down("a", sortId).innerHTML = controlName;
            CreateWinControl(_controlname, 'navigationlist');
        });
    }
}

/* 作者：孟龙
功能：头部导航设置内容更新
时间: 2012年9月14日12:02:11 */
function updateNavigation(controlName, sortid, isShow, upordownname, _controlname, callBack) {
    var url = "/AJAXpage/UpdateNavigation";
    var _selectby =
    "_Name=" + encodeURI(controlName) + "&_Sort=" + sortid + "&_ControlName=" + _controlname + "&_IsShow=" + isShow +
    "&_UpOrDownName=" + upordownname;
    var myAjax = new Ajax.Request(url, { method: "get",
        parameters: _selectby,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (reqdata) {
            if (reqdata.responseText == "1") {
                if (callBack) {
                    callBack();
                    $("message").show();
                    setInterval(function () {
                        $("message").hide();
                    }, 3000);
                }
            }
        }, asynchronous: false
    });
}
/* 作者：孟龙
功能：显示、隐藏导航上栏目
时间：2012年9月17日16:00:22 */
function hideOrShowNavigation(sender, _controlname) {
    var checkedLength = 0;
    var d = $$("input[name=check_1]").each(function (item) {
        if (item.checked) {
            checkedLength++;
        }
    });
    if (checkedLength < 6 && checkedLength >= 0) {
        var isShowOrHide = $(sender).checked ? 1 : 0;
        var hid_sort = $(sender).up("div");
        var sortId = hid_sort.readAttribute("value");
        updateNavigation("", sortId, isShowOrHide, "", _controlname, function () {
            //取消
            if (isShowOrHide == 0) {
                $("menu").down("li", sortId).writeAttribute("style", "position:absolute; visibility: hidden");
            }
            //添加
            else {
                $("menu").down("li", sortId).writeAttribute("style", "");
            }
        });
    }
    else if (checkedLength > 5) {
        if ($(sender).checked) {
            $(sender).checked = false;
            alert("导航最多可呈现5个栏目，请隐藏一个再进行选择！");
        }
    }
}
/* 作者：孟龙
功能：对导航进行上、下位排序
时间：2012年9月19日11:50:50*/
function upOrDownNavigation(sender, _controlname) {
    var upOrDownName = $(sender).readAttribute("name");
    var navigationSort = Number($(sender).readAttribute("value"));
    updateNavigation("", navigationSort, "", upOrDownName, _controlname, function () {
        var url = '/AJAXpage/CreateHtml';
        var _pars = 'controlName=' + _controlname + "&colLayout=0";
        var myAjax = new Ajax.Request(url, { method: 'get',
            parameters: _pars,
            requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
            onComplete: function (req) {
                $("divNav").innerHTML = req.responseText;
                // CreateWinControl(_controlname, "navigationlist"); //填充
            }, asynchronous: false
        });

        var current = $(sender).up("div");
        var imgTr = current.down("tr");
        var imgTd = imgTr.down("td", 2);
        if (upOrDownName == "upNavigation") {
            var upcurrent = current.previous("div");
            var uptr = upcurrent.down("tr");
            var uptd = uptr.down("td", 3);
            var divValue = current.readAttribute("value");
            if (divValue == 1) {
                uptd.down().show();
            }
            if (divValue == 6) {
                uptd.down(1).hide();
            }
            if (upcurrent) {
                upcurrent.insert(
                        { before: current }
                        );
                $(sender).writeAttribute("value", navigationSort - 1);
                $(sender).next().writeAttribute("value", navigationSort - 1);
                uptd.down(0).writeAttribute("value", navigationSort);
                uptd.down(1).writeAttribute("value", navigationSort);
                current.writeAttribute("value", navigationSort - 1);
                upcurrent.writeAttribute("value", navigationSort);
            }
            showOrDisplay();
        }
        else if (upOrDownName == "downNavigation") {
            var downcurrent = current.next("div");
            var downtr = downcurrent.down();
            var downtd = downtr.down("td", 3);
            var divValue = current.readAttribute("value");
            if (divValue == 5) {
                downtd.down(1).show();
            }
            if (divValue == 0) {
                downtd.down().hide();
            }
            if (downcurrent) {
                downcurrent.insert(
                        { after: current }
                        );
                $(sender).writeAttribute("value", navigationSort + 1);
                $(sender).previous().writeAttribute("value", navigationSort + 1);
                downtd.down(0).writeAttribute("value", navigationSort);
                downtd.down(1).writeAttribute("value", navigationSort);
                current.writeAttribute("value", navigationSort + 1);
                downcurrent.writeAttribute("value", navigationSort);
            }
            showOrDisplay();
        }

        //        var obj = getInitUserStyle();
        //        if (obj != false) {
        //            initNavigationListStyle(obj);
        //        }
    });
}

/* 作者：孟龙
功能：初始化页面上下移位按钮图片（限制显示）
时间：2012年9月21日12:22:53 */

function showOrDisplay() {
    var upnav = document.getElementsByName("upNavigation");
    for (var i = 0; i < upnav.length; i++) {
        var divvalue = upnav[i].up("div").readAttribute("value");
        if (divvalue == 0) {
            upnav[i].style.display = "none";
        }
        if (divvalue == 1) {
            upnav[i].style.display = "";
        }
    }
    var downnav = document.getElementsByName("downNavigation");
    for (var i = 0; i < downnav.length; i++) {
        var alue = downnav[i].up("div").readAttribute("value");
        if (alue == 6) {
            downnav[i].style.display = "none";
        }
        if (alue == 5) {
            downnav[i].style.display = "";
        }
    }
}

/* 作者：孟龙
功能：更新Logo上字体样式及是否显示
时间：2012年9月28日11:25:29 */
//setWinCallBack = function () {
//    $("aSaveLogo").observe("click", function () {
//        var fontcolor = $("singleImgBanner").style.backgroundColor;
//        updateLogoMessage($("show").checked ? 1 : 0, "Col_Logo", $F("fontfamily"), $F("fontsize"),
//        $('logofont').hasClassName('logofont1') ? 1 : 0, $('fontitalic').hasClassName('fontitalic1') ? 1 : 0, fontcolor, function () {
//            var url = 'http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/CreateHtml.ashx';
//            var _select = "controlName=Col_Logo&colLayout=0";
//            var myAjax = new Ajax.Request(url, { method: 'get',
//                parameters: _select,
//                requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
//                onComplete: function (req) {
//                    $("logoChange").innerHTML = req.responseText;
//                }, asynchronous: false
//            });
//        }); WinClose();
//    });
//};

function showOrHideMessage() {
    var showName = $("show");
    if (showName.checked) {
        $("win_isshow").show();
        $("companyName").show();
        $("font_bold").show();
    }
    else {
        $("win_isshow").hide();
        $("companyName").hide();
        $("font_bold").hide();
    }
}

function updateLogoMessage(isshow, controlname, fontfamily, fontsize, isfontbold, isfontitalic, fontcolorS, callBack) {
    var url = 'http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/UpdateLogoMessage.ashx';
    var _selectby = "_IsShow=" + isshow + "&_ControlName=" + controlname + "&_FontFamily=" + fontfamily + "&_FontSize=" + fontsize +
    "&_IsFontBold=" + isfontbold + "&_IsFontItalic=" + isfontitalic + "&_FontColor=" + fontcolorS;
    var myAjax = new Ajax.Request(url, { method: 'get',
        parameters: _selectby,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {
                if (callBack) {
                    callBack();
                }
            }
            else {
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}

/* 更新banner上字体及其样式 */
function updateBannerMessage(controlname, isshow, callBack) {
    var url = 'AJAXpage/UpdateBannerContent.ashx?_conName=' + controlname + '&_isShow=' + isshow;
    var myAjax = new Ajax.Request(url, { method: 'get',
        parameters: $("form_banner").serialize(),
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {
                if (callBack) {
                    callBack();
                }
            }
            else {
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}

function changeContent() {
    updateBannerMessage("col_banner", $("showbanner").checked ? 1 : 0, function () {
        var url = 'http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/CreateHtml.ashx';
        var _select = "controlName=Col_Banner&colLayout=0";
        var myAjax = new Ajax.Request(url, { method: 'get',
            parameters: _select,
            requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
            onComplete: function (req) {
                $("bannerchange").innerHTML = req.responseText;
            }, asynchronous: false
        });
    }); WinClose();
}

function changeRidaoforBanner() {
    var showname = $("showbanner");
    var tempElements = document.getElementsByClassName("messageshow");
    for (var i = 0; i < tempElements.length; i++) {
        var tempElement = tempElements[i];
        var tempElementId = tempElement.id;
        if (showname.checked) {
            $(tempElementId).show();
        }
        else {
            $(tempElementId).hide();
        }
    }
    if (showname.checked) {
        $("messageforbanner").show();
    }
    else {
        $("messageforbanner").hide();
    }
}

function assignmentInput(valeColor, theObj) {
    $(theObj).previous().value = valeColor;
}

/* 发布 */
function publishWebSite() {
    var url = '/AJAXpage/PublishWebSite';
    var myAjax = new Ajax.Request(url, { method: 'get',
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "0") {   //成功！
                alert("生成网站成功！");
            } else {   //失败
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}

function saveCumtomStyle(colValue, colName, modifyFun) {
    if (modifyFun != "undefined" && modifyFun != "null") {

        eval(modifyFun + "(colValue,colName)");
    }
    var url = '/AJAXpage/CustomStyle';
    var par = "attributeName=" + colName + "&attributeValue=" + colValue;
    var myAjax = new Ajax.Request(url, { method: 'get',
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        parameters: par,
        onComplete: function (req) {
            if (req.responseText != "undefined" && req.responseText != null) {   //成功！
                var docContent = $("doc");
                docContent.writeAttribute("config-style-data", req.responseText);
            } else {   //失败

            }
        }, asynchronous: true
    });
}


//function setBackgroundStyle(colValue, colName) {

//    var colDoc = document.getElementById("doc");
//    var setStyle = colName.split('.')[1];
//    switch (setStyle) {
//        case "BgColor":
//            colDoc.style.backgroundColor = colValue;
//            break;
//        case "BgImage":
//            colDoc.style.backgroundImage = "url(" + colValue + ")";
//            var sel = $('pBgorudSelect');
//            if (typeof (sel) != "undefined" && sel != null) {
//                setBgImageRepeat(sel.options[sel.selectedIndex].value, colDoc);
//            }
//            break;
//        case "BgImageRepeat":
//            setBgImageRepeat(colValue, colDoc);
//            break;
//        case "BgImagePosition":
//            initBgImagePosition(colValue);
//            switch (colValue) {
//                case "top left":
//                    colDoc.style.backgroundPosition = "top left";
//                    break;
//                case "top center":
//                    colDoc.style.backgroundPosition = "top center";
//                    break;
//                case "top right":
//                    colDoc.style.backgroundPosition = "top right";
//                    break;
//                default:
//                    break;
//            }
//            break;
//        default:
//            break;
//    }
//}

//function setBgImageRepeat(colValue, obj) {
//    switch (colValue) {
//        case "0":
//            obj.style.backgroundRepeat = "repeat";
//            break;
//        case "1":
//            obj.style.backgroundRepeat = "repeat-x";
//            break;
//        case "2":
//            obj.style.backgroundRepeat = "repeat-y";
//            break;
//        case "3":
//            obj.style.backgroundRepeat = "no-repeat";
//            break;
//        default:
//            break;
//    }
//}

function setNavDefaultStyle(colValue, colName) {
    var colBg = document.getElementById("menu");
    var navStyle = colName.split('.')[1];
    switch (navStyle) {
        case "BgColor":
            colBg.style.backgroundColor = colValue;
            break;
        case "BgImage":
            colBg.style.backgroundImage = "url(" + colValue + ")";
            break;
        default:
            break;
    }
    var colList = document.getElementById("menu").getElementsByTagName("a");
    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        switch (navStyle) {
            case "TextColor":
                var txt_NavTextColor = $("txt_NavTextColor");
                txt_NavTextColor.value = colValue;
                col.style.color = colValue;
                break;
            default:
                break;
        }
    }
}

function setNavModifyStyle(colValue, colName) {
    var colList = document.getElementById("menu").getElementsByTagName("a");

    var navStyle = colName.split('.')[1];
    switch (navStyle) {
        case "HoverBgColor":
            var txt_NavHoverBgColor = $("txt_NavHoverBgColor");
            txt_NavHoverBgColor.value = colValue;
            break;
        case "HoverBgImage":
            var txt_NavHoverBgImage = $("txt_NavHoverBgImage");
            txt_NavHoverBgImage.value = colValue;
            break;
        case "HoverTextColor":
            var txt_NavHoverTextColor = $("txt_NavHoverTextColor");
            txt_NavHoverTextColor.value = colValue;
            break;
        default:
            break;
    }

    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        col.onmouseover = function () {
            var colNavBgColor = $("colNavModifyBgColor")
            if (colNavBgColor != null) {
                this.style.backgroundColor = colNavBgColor.style.backgroundColor;
            }
            else {
                var txt_NavHoverBgColor = $("txt_NavHoverBgColor");
                this.style.backgroundColor = txt_NavHoverBgColor.value;
            }

            var colNavTextColor = $("colNavModifyTextColor")
            if (colNavTextColor != null) {
                this.style.color = colNavTextColor.style.backgroundColor;
            }
            else {
                var txt_NavHoverTextColor = $("txt_NavHoverTextColor");
                this.style.color = txt_NavHoverTextColor.value;
            }
            var colNavBgImage = $("txt_NavListHoverImgurl")
            if (colNavBgImage != null) {
                this.style.backgroundImage = "url(" + colNavBgImage.value + ")";
            }
            else {
                var txt_NavHoverBgImage = $("txt_NavHoverBgImage");
                this.style.backgroundImage = "url(" + txt_NavHoverBgImage.value + ")";
            }
        }
        col.onmouseout = function () {
            this.style.background = "";

            var colFontColor = $("colNavFontColor")
            if (colFontColor != null) {
                this.style.color = colFontColor.style.backgroundColor;
            }
            else {
                var txt_NavTextColor = $("txt_NavTextColor");
                this.style.color = txt_NavTextColor.value;
            }
        }
    }
}

function clearNavModifyStyle() {
    var colList = document.getElementById("menu").getElementsByTagName("a");
    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        col.onmouseover = function () {
            var colNavBgColor = $("colNavModifyBgColor")
            this.style.background = colNavBgColor.style.backgroundColor;
            var colNavTextColor = $("colNavModifyTextColor")
            this.style.color = colNavTextColor.style.backgroundColor;
            $("txt_NavListHoverImgurl").removeAttribute("value");
        }
        col.onmouseout = function () {
            this.style.background = "";
            var colFontColor = $("colNavFontColor")
            this.style.color = colFontColor.style.backgroundColor;
        }
    }
}

function clearNavCustomStyle() {
    var colBg = document.getElementById("menu");
    //colBg.removeAttribute("style");
    colBg.setAttribute("style", "");
    var colList = document.getElementById("menu").getElementsByTagName("a");
    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        col.setAttribute("style", "");
        col.onmouseover = function () { };
        col.onmouseout = function () { };
        //        col.detachEvent("onmouseover");
        //        col.detachEvent("onmouseout");
    }
}

function modifyNavBgColor(colValue, colName) {
    var col = $("navBg");
    if (colValue == 1) {
        col.hide();
        clearNavCustomStyle();
    }
    else {
        col.show();
        var obj = getInitUserStyle();
        if (obj != false && typeof (obj.MobSpNavigationListStyle.IsDefault) != "undefined" && obj.MobSpNavigationListStyle.IsDefault != null) {
            obj.MobSpNavigationListStyle.IsDefault = 0;
            initNavigationListStyle(obj)
        }
    }
}

//function modifyPageBackground(colValue, colName) {
//    var col_1 = $("BgColor");
//    var col_2 = $("BgImage");
//    var col_3 = $("BgRepeat");
//    var col_4 = $("BgPosition");
//    var col = $("doc");
//    var col_BgColor = $("colBgColor");
//    if (colValue == 1) {
//        col_1.hide();
//        col_2.hide();
//        col_3.hide();
//        col_4.hide();
//        col.removeAttribute("style");
//    }
//    else {
//        col_1.show();
//        col_2.show();
//        col_3.show();
//        col_4.show();
//        var obj = getInitUserStyle();
//        if (obj != false && typeof (obj.SpPageBackground.IsDefault) != "undefined" && obj.SpPageBackground.IsDefault != null) {
//            obj.SpPageBackground.IsDefault = 0;
//            initBackgroundStyle(obj)
//        }
//    }
//}

function modifyControlStyle(colValue, colName) {
    var doc = $("divColStyle")
    if (colValue == 1) {
        doc.style.display = "none";
        clearColStyle();
    }
    else {
        doc.style.display = "block";
        var obj = getInitUserStyle();
        if (obj != false && typeof (obj.MobSpControlStyle.IsDefault) != "undefined" && obj.MobSpControlStyle.IsDefault != null) {
            obj.MobSpControlStyle.IsDefault = 0;
            initColStyle(obj)
        }
    }
}

function setCollTitleStyle(colValue, colName) {
    var colList = document.getElementsByClassName("title");

    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        var setType = colName.split(".")[1];
        switch (setType) {
            case "TitleBgColor":
                col.style.background = colValue;
                break;
            case "TitleBgImage":
                col.style.backgroundImage = "url(" + colValue + ")";
                break;
            case "TitleTextColor":
                col.style.color = colValue;
                var blist = col.getElementsByTagName("b");
                if (typeof (blist[0]) != "undefined" && blist[0] != null) {
                    blist[0].style.color = colValue;
                }
                break;
            default:
                break;
        }
    }
}

function setCollContentStyle(colValue, colName) {
    var colList = document.getElementsByClassName("content_box");
    var setType = colName.split(".")[1];
    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        switch (setType) {
            case "IsContentBorder":
                if (colValue == 1) {
                    col.style.borderWidth = "0px";
                }
                else {
                    col.style.borderWidth = "1px";
                }
                break;
            case "ContentBorderColor":
                col.style.borderColor = colValue;
                break;
            case "ContentOpacity":
                var alpha;
                if (colValue == 1) {
                    alpha = 100;
                }
                else {
                    alpha = 50;
                }
                col.style.filter = "alpha(opacity = " + alpha + ")";
                col.style.opacity = colValue;
                break;
            case "ContentTextColor":
                col.style.color = colValue;
                break;
            case "ContentLinkColor":
                var alist = col.getElementsByTagName("a")
                for (var j = 0; j < alist.length; j++) {
                    var aDoc = alist[j];
                    aDoc.style.color = colValue;
                }
            default:
                break;
        }
    }
    var colList10 = document.getElementsByClassName("content p10");
    if (colList10[0] != null && colList10.length > 0) {
        for (var u = 0; u < colList10.length; u++) {
            var col_p10 = colList10[u];
            switch (setType) {
                case "IsContentBorder":
                    if (colValue == 1) {
                        col_p10.style.borderWidth = "0px";
                    }
                    else {
                        col_p10.style.borderWidth = "1px";
                    }
                    break;
                case "ContentBorderColor":
                    col_p10.style.borderColor = colValue;
                    break;
                case "ContentOpacity":
                    var alpha;
                    if (colValue == 1) {
                        alpha = 100;
                    }
                    else {
                        alpha = 50;
                    }
                    col_p10.style.filter = "alpha(opacity = " + alpha + ")";
                    col_p10.style.opacity = colValue;
                    break;
                case "ContentTextColor":
                    col_p10.style.color = colValue;
                    break;
                case "ContentLinkColor":
                    var alist = col_p10.getElementsByTagName("a")
                    for (var j = 0; j < alist.length; j++) {
                        var aDoc = alist[j];
                        aDoc.style.color = colValue;
                    }
                default:
                    break;
            }
        }
    }
    var colLink = document.getElementsByClassName("content link");
    if (colLink[0] != null) {
        var col_link = colLink[0];
        switch (setType) {
            case "IsContentBorder":
                if (colValue == 1) {
                    col_link.style.borderWidth = "0px";
                }
                else {
                    col_link.style.borderWidth = "1px";
                }
                break;
            case "ContentBorderColor":
                col_link.style.borderColor = colValue;
                break;
            case "ContentOpacity":
                var alpha;
                if (colValue == 1) {
                    alpha = 100;
                }
                else {
                    alpha = 50;
                }
                col_link.style.filter = "alpha(opacity = " + alpha + ")";
                col_link.style.opacity = colValue;
                break;
            case "ContentTextColor":
                col_link.style.color = colValue;
                break;
            case "ContentLinkColor":
                var alist = col_link.getElementsByTagName("a")
                for (var j = 0; j < alist.length; j++) {
                    var aDoc = alist[j];
                    aDoc.style.color = colValue;
                }
            default:
                break;
        }
    }
    var colSer = document.getElementsByClassName("content ser");
    if (colSer[0] != null) {
        var col_ser = colSer[0];
        switch (setType) {
            case "IsContentBorder":
                if (colValue == 1) {
                    col_ser.style.borderWidth = "0px";
                }
                else {
                    col_ser.style.borderWidth = "1px";
                }
                break;
            case "ContentBorderColor":
                col_ser.style.borderColor = colValue;
                break;
            case "ContentOpacity":
                var alpha;
                if (colValue == 1) {
                    alpha = 100;
                }
                else {
                    alpha = 50;
                }
                col_ser.style.filter = "alpha(opacity = " + alpha + ")";
                col_ser.style.opacity = colValue;
                break;
            case "ContentTextColor":
                //                var classlist = col_ser.getElementByTagName("dd")
                //                for (var l = 0; l < classlist.length; l++) {
                //                    var lDoc = classlist[l];
                //                    lDoc.style.color = colValue;
                //                }
                col_ser.style.color = colValue;
                break;
            case "ContentLinkColor":
                var alist = col_ser.getElementsByTagName("a")
                for (var j = 0; j < alist.length; j++) {
                    var aDoc = alist[j];
                    aDoc.style.color = colValue;
                }
            default:
                break;
        }
    }
    var colSort = document.getElementsByClassName("content sort");
    if (colSort[0] != null) {
        var col_sort = colSort[0];
        switch (setType) {
            case "IsContentBorder":
                if (colValue == 1) {
                    col_sort.style.borderWidth = "0px";
                }
                else {
                    col_sort.style.borderWidth = "1px";
                }
                break;
            case "ContentBorderColor":
                col_sort.style.borderColor = colValue;
                break;
            case "ContentOpacity":
                var alpha;
                if (colValue == 1) {
                    alpha = 100;
                }
                else {
                    alpha = 50;
                }
                col_sort.style.filter = "alpha(opacity = " + alpha + ")";
                col_sort.style.opacity = colValue;
                break;
            case "ContentTextColor":
                col_sort.style.color = colValue;
                break;
            case "ContentLinkColor":
                var alist = col_sort.getElementsByTagName("a")
                for (var j = 0; j < alist.length; j++) {
                    var aDoc = alist[j];
                    aDoc.style.color = colValue;
                }
            default:
                break;
        }
    }
    var colNews = document.getElementsByClassName("content news");
    if (colNews[0] != null) {
        var col_news = colNews[0];
        switch (setType) {
            case "IsContentBorder":
                if (colValue == 1) {
                    col_news.style.borderWidth = "0px";
                }
                else {
                    col_news.style.borderWidth = "1px";
                }
                break;
            case "ContentBorderColor":
                col_news.style.borderColor = colValue;
                break;
            case "ContentOpacity":
                var alpha;
                if (colValue == 1) {
                    alpha = 100;
                }
                else {
                    alpha = 50;
                }
                col_news.style.filter = "alpha(opacity = " + alpha + ")";
                col_news.style.opacity = colValue;
                break;
            case "ContentTextColor":
                col_news.style.color = colValue;
                break;
            case "ContentLinkColor":
                var alist = col_news.getElementsByTagName("a")
                for (var j = 0; j < alist.length; j++) {
                    var aDoc = alist[j];
                    aDoc.style.color = colValue;
                }
            default:
                break;
        }
    }
    var colpic = document.getElementsByClassName("content pic");
    if (colpic[0] != null) {
        var col_pic = colpic[0];
        switch (setType) {
            case "IsContentBorder":
                if (colValue == 1) {
                    col_pic.style.borderWidth = "0px";
                }
                else {
                    col_pic.style.borderWidth = "1px";
                }
                break;
            case "ContentBorderColor":
                col_pic.style.borderColor = colValue;
                break;
            case "ContentOpacity":
                var alpha;
                if (colValue == 1) {
                    alpha = 100;
                }
                else {
                    alpha = 50;
                }
                col_pic.style.filter = "alpha(opacity = " + alpha + ")";
                col_pic.style.opacity = colValue;
                break;
            case "ContentTextColor":
                col_pic.style.color = colValue;
                break;
            case "ContentLinkColor":
                var alist = col_pic.getElementsByTagName("a")
                for (var j = 0; j < alist.length; j++) {
                    var aDoc = alist[j];
                    aDoc.style.color = colValue;
                }
            default:
                break;
        }
    }
}

function clearColStyle() {

    var colTList = document.getElementsByClassName("title");

    for (var i = 0; i < colTList.length; i++) {
        var col = colTList[i];
        col.setAttribute("style", "");
        var bTList = col.getElementsByTagName("b");
        if (typeof (bTList[0]) != "undefined" && bTList[0] != null) {
            bTList[0].setAttribute("style", "");
        }
    }
    var colCList = document.getElementsByClassName("content_box");
    for (var i = 0; i < colCList.length; i++) {
        var col = colCList[i];
        col.setAttribute("style", "");
        var aCList = col.getElementsByTagName("a")
        for (var j = 0; j < aCList.length; j++) {
            var aDoc = aCList[j]
            aDoc.setAttribute("style", "");
        }
    }
}

function modifyPageLayout(colValue, colName) {
    var leftBox = $("dom0");
    var rightBox = $("dom1");
    if (typeof (leftBox) != "undefined" && leftBox != null && typeof (rightBox) != "undefined" && rightBox != null) {
        var col0 = $("PageLayOut0");
        var col1 = $("PageLayOut1");
        if (colValue == 0) {
            leftBox.style.cssFloat = leftBox.style.styleFloat = "left";
            rightBox.style.cssFloat = rightBox.style.styleFloat = "right";

            if (col0 != null) {
                col0.className = "greentab";
            }
            if (col1 != null) {
                col1.className = "greentab1";
            }

            var leftControls = document.getElementsByClassName("ltab");
            for (var i = 0; i < leftControls.length; i++) {
                var leftCol = leftControls[i];
                leftCol.style.cssFloat = leftCol.style.styleFloat = "left";
            }
            var rightControls = document.getElementsByClassName("rtab");
            for (var j = 0; j < rightControls.length; j++) {
                var rightCol = rightControls[j];
                rightCol.style.cssFloat = rightCol.style.styleFloat = "right";
            }
        }
        if (colValue == 1) {
            leftBox.style.cssFloat = leftBox.style.styleFloat = "right";
            rightBox.style.cssFloat = rightBox.style.styleFloat = "left";

            if (col0 != null) {
                col0.className = "greentab1";
            }
            if (col1 != null) {
                col1.className = "greentab";
            }

            var leftControls = document.getElementsByClassName("ltab");
            for (var i = 0; i < leftControls.length; i++) {
                var leftCol = leftControls[i];
                leftCol.style.cssFloat = leftCol.style.styleFloat = "right";
            }
            var rightControls = document.getElementsByClassName("rtab");
            for (var j = 0; j < rightControls.length; j++) {
                var rightCol = rightControls[j];
                rightCol.style.cssFloat = rightCol.style.styleFloat = "left";
            }
        }
    }
}

function saveIsContentBorder(colValue, colName, modifyFun) {
    var cbBorder = $(colValue);
    var value;
    if (cbBorder.checked) {
        value = 1;
    }
    else {
        value = 0;
    }
    saveCumtomStyle(value, colName, modifyFun)
}

function saveContentOpacity(colValue, colName, modifyFun) {
    var cbBorder = $(colValue);
    var value;
    if (cbBorder.checked) {
        value = 0.5;
    }
    else {
        value = 1;
    }
    saveCumtomStyle(value, colName, modifyFun)
}

function saveTitleBgImage(id, filenameid, accpetid, colName, modifyFun) {
    qhwUpload.displaySWF();
    var swfUploadControl = qhwUpload.getInstance({
        uploadUrl: "/AJAXpage/FileUpload",
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        placeHoldId: id,
        fileQueued: function (file) {
            swfUploadControl.startUpload();
            $(filenameid).innerHTML = file.name;
            $(filenameid).show();
        },
        uploadSuccess: function (file, serverData) {
            if (serverData != "") {
                document.getElementById(accpetid).value = serverData;
                saveCumtomStyle(serverData, colName, modifyFun);
            }
        }
    });
}


function saveNavListBgImage() {
    saveTitleBgImage('span_NavListDefaultuploadButton', 'div_NavListDefaultfileName', 'txt_NavListDefaultImgurl', 'NavigationListStyle.BgImage', 'setNavDefaultStyle')
    saveTitleBgImage('span_NavListHoveruploadButton', 'div_NavListHoverfileName', 'txt_NavListHoverImgurl', 'NavigationListStyle.HoverBgImage', 'setNavModifyStyle')
}

function initColStyle(obj) {
    ;
    if (obj.MobSpControlStyle != null && typeof (obj.MobSpControlStyle.IsDefault) != "undefined" && obj.MobSpControlStyle.IsDefault != null) {

        if (obj.MobSpControlStyle.IsDefault != 1) {
            var rd = $("rdCustomControlStyle");
            if (rd != null) {
                rd.checked = true;
            }

            if (typeof (obj.MobSpControlStyle.TitleBgColor) != "undefined" && obj.MobSpControlStyle.TitleBgColor != null) {
                setCollTitleStyle(obj.MobSpControlStyle.TitleBgColor, "ControlStyle.TitleBgColor");
            }
            if (typeof (obj.MobSpControlStyle.TitleBgImage) != "undefined") {
                setCollTitleStyle(obj.MobSpControlStyle.TitleBgImage, "ControlStyle.TitleBgImage");
            }
            if (typeof (obj.MobSpControlStyle.TitleTextColor) != "undefined" && obj.MobSpControlStyle.TitleTextColor != null) {
                setCollTitleStyle(obj.MobSpControlStyle.TitleTextColor, "ControlStyle.TitleTextColor");
            }

            if (typeof (obj.MobSpControlStyle.IsContentBorder) != "undefined" && obj.MobSpControlStyle.IsContentBorder != null) {
                setCollContentStyle(obj.MobSpControlStyle.IsContentBorder, "ControlStyle.IsContentBorder");
            }
            if (typeof (obj.MobSpControlStyle.ContentBorderColor) != "undefined" && obj.MobSpControlStyle.ContentBorderColor != null) {
                setCollContentStyle(obj.MobSpControlStyle.ContentBorderColor, "ControlStyle.ContentBorderColor");
            }
            if (typeof (obj.MobSpControlStyle.ContentOpacity) != "undefined" && obj.MobSpControlStyle.ContentOpacity != null) {
                setCollContentStyle(obj.MobSpControlStyle.ContentOpacity, "ControlStyle.ContentOpacity");
            }
            if (typeof (obj.MobSpControlStyle.ContentTextColor) != "undefined" && obj.MobSpControlStyle.ContentTextColor != null) {
                setCollContentStyle(obj.MobSpControlStyle.ContentTextColor, "ControlStyle.ContentTextColor");
            }
            if (typeof (obj.MobSpControlStyle.ContentLinkColor) != "undefined" && obj.MobSpControlStyle.ContentLinkColor != null) {
                setCollContentStyle(obj.MobSpControlStyle.ContentLinkColor, "ControlStyle.ContentLinkColor");
            }
        }
        else {
            var rd = $("rdDefaultControlStyle");
            if (rd != null) {
                rd.checked = true;
            }
        }
    }
}

function initNavigationListStyle(obj) {
    if (obj.MobSpNavigationListStyle != null && typeof (obj.MobSpNavigationListStyle.IsDefault) != "undefined" && obj.MobSpNavigationListStyle.IsDefault != null) {
        if (obj.MobSpNavigationListStyle.IsDefault != 1) {
            var rd = $("radioCumtomNav");
            if (rd != null) {
                rd.checked = true;
            }

            if (typeof (obj.MobSpNavigationListStyle.HoverBgColor) != "undefined" && obj.MobSpNavigationListStyle.HoverBgColor != null) {
                setNavModifyStyle(obj.MobSpNavigationListStyle.HoverBgColor, "NavigationListStyle.HoverBgColor");
            }
            if (typeof (obj.MobSpNavigationListStyle.HoverBgImage) != "undefined" && obj.MobSpNavigationListStyle.HoverBgImage != null) {
                setNavModifyStyle(obj.MobSpNavigationListStyle.HoverBgImage, "NavigationListStyle.HoverBgImage");
            }
            if (typeof (obj.MobSpNavigationListStyle.HoverTextColor) != "undefined" && obj.MobSpNavigationListStyle.HoverTextColor != null) {
                setNavModifyStyle(obj.MobSpNavigationListStyle.HoverTextColor, "NavigationListStyle.HoverTextColor");
            }

            if (typeof (obj.MobSpNavigationListStyle.BgColor) != "undefined" && obj.MobSpNavigationListStyle.BgColor != null) {
                setNavDefaultStyle(obj.MobSpNavigationListStyle.BgColor, "NavigationListStyle.BgColor");
            }
            if (typeof (obj.MobSpNavigationListStyle.BgImage) != "undefined" && obj.MobSpNavigationListStyle.BgImage != null) {
                setNavDefaultStyle(obj.MobSpNavigationListStyle.BgImage, "NavigationListStyle.BgImage");
            }
            if (typeof (obj.MobSpNavigationListStyle.TextColor) != "undefined" && obj.MobSpNavigationListStyle.TextColor != null) {
                setNavDefaultStyle(obj.MobSpNavigationListStyle.TextColor, "NavigationListStyle.TextColor");
            }
        }
        else {
            var rd = $("radioDefaultNav");
            if (rd != null) {
                rd.checked = true;
            }
        }
    }
}

function initBgImagePosition(colValue) {
    var bgImagePositionLeft = $("BgImagePositionLeft");
    var bgImagePositionCenter = $("BgImagePositionCenter");
    var bgImagePositionRight = $("BgImagePositionRight");
    if (typeof (bgImagePositionLeft) != "undefined" && bgImagePositionLeft != null) {
        switch (colValue) {
            case "top left":
                bgImagePositionLeft.className = "lihover ";
                bgImagePositionCenter.className = "";
                bgImagePositionRight.className = "";
                break;
            case "top center":
                bgImagePositionLeft.className = "";
                bgImagePositionCenter.className = "mhover";
                bgImagePositionRight.className = "";
                break;
            case "top right":
                bgImagePositionLeft.className = "";
                bgImagePositionCenter.className = "";
                bgImagePositionRight.className = "rhover";
                break;
            default:
                break;
        }
    }
}

function initBackgroundStyle(obj) {
    if (obj.SpPageBackground != null && (obj.SpPageBackground.IsDefault) != "undefined" && obj.SpPageBackground.IsDefault != null) {
        if (obj.SpPageBackground.IsDefault != 1) {
            var rd = $("radioCustomPageBg");
            if (rd != null) {
                rd.checked = true;
            }
            if (typeof (obj.SpPageBackground.BgColor) != "undefined" && obj.SpPageBackground.BgColor != null) {
                setBackgroundStyle(obj.SpPageBackground.BgColor, "PageBackground.BgColor");
            }
            if (typeof (obj.SpPageBackground.BgImagePosition) != "undefined" && obj.SpPageBackground.BgImagePosition != null) {
                setBackgroundStyle(obj.SpPageBackground.BgImagePosition, "PageBackground.BgImagePosition");
            }
            if (typeof (obj.SpPageBackground.BgImageRepeat) != "undefined" && obj.SpPageBackground.BgImageRepeat != null) {
                setBackgroundStyle(obj.SpPageBackground.BgImageRepeat, "PageBackground.BgImageRepeat");
                var sel = $('pBgorudSelect');
                if (typeof (sel) != "undefined" && sel != null) {
                    for (i = 0; i < sel.length; i++) {
                        if (sel.options[i].value == obj.SpPageBackground.BgImageRepeat) {
                            sel.options[i].selected = true;
                        }
                    }
                }
            }
            if (typeof (obj.SpPageBackground.BgImage) != "undefined") {
                setBackgroundStyle(obj.SpPageBackground.BgImage, "PageBackground.BgImage");
            }

        }
        else {
            var rd = $("radioDefaultPageBg");
            if (rd != null) {
                rd.checked = true;
            }
        }
    }
}

function initPageLayout(obj) {
    if (typeof (obj.SpEpageLayOut) != "undefined" && obj.SpEpageLayOut != null) {
        modifyPageLayout(obj.SpEpageLayOut, "EpageLayOut");
    }
}

function getInitUserStyle() {
    var doc = $("doc");
    var str = doc.getAttribute("config-style-data");
    var obj = JSON.parse(str);
    return obj;
}

function initUserStyleData() {
    var obj = getInitUserStyle();

    if (obj != false) {
        initColStyle(obj)
        initNavigationListStyle(obj)
        initBackgroundStyle(obj)
        initPageLayout(obj);
    }
}

function clearPageBgImage(colFileName, colName, func) {
    var pageBgImage = $(colFileName);
    pageBgImage.innerHTML = "";
    saveCumtomStyle('', colName, func)
}


function checkImage(obj, w, h) {

    var ImgCell = obj.getElementsByTagName("img");
    for (var i = 0; i < ImgCell.length; i++) {

        ImgCell[i].removeAttribute("width");
        ImgCell[i].removeAttribute("height");

        var ImgWidth = ImgCell[i].width;
        var ImgHeight = ImgCell[i].height;


        if (ImgWidth > w) {
            var newHeight = h * ImgHeight / ImgWidth;
            var newWidth = w * ImgWidth / ImgHeight;
            if (newHeight <= h) {

                ImgCell[i].width = w;
                ImgCell[i].height = newHeight;
            }
            else {

                ImgCell[i].height = h;
                ImgCell[i].width = newWidth;
            }
        }
        else {
            if (ImgHeight > h) {

                ImgCell[i].height = h;
                ImgCell[i].width = newWidth;
            }
            else {

                ImgCell[i].height = ImgHeight;
                ImgCell[i].width = ImgWidth;
            }
        }
    }
}

function modifyAllImage() {

    var picCon = document.getElementsByClassName("content pic");
    if (picCon != null && picCon.length > 0) {
        if (picCon[0].parentNode.className == 'rtab') {
            checkImage(picCon[0], 164, 164);
        }
        else {
            checkImage(picCon[0], 60, 60);
        }
    }

    var honorCon = document.getElementsByClassName("content honor");
    if (honorCon != null && honorCon.length > 0) {
        if (honorCon[0].parentNode.className == 'rtab') {
            checkImage(honorCon[0], 164, 164);
        }
        else {
            checkImage(honorCon[0], 60, 60);
        }
    }

    var proCon = document.getElementsByClassName("content pro");
    for (i = 0; i < proCon.length; i++) {
        if (proCon != null && proCon.length > 0) {
            if (proCon[i].parentNode.className == 'rtab') {
                checkImage(proCon[i], 164, 164);
            }
            else {
                checkImage(proCon[i], 60, 60);
            }
        }
    }
    var pscCon = document.getElementsByClassName("content psc");
    if (pscCon != null && pscCon.length > 0) {
        if (pscCon[0].parentNode.className == 'rtab') {
            checkImage(pscCon[0], 164, 164);
        }
        else {
            checkImage(pscCon[0], 60, 60);
        }
    }


    var scrollCon = document.getElementsByClassName("content roll");
    if (scrollCon != null && scrollCon.length > 0) {
        if (scrollCon[0].parentNode.className == 'rtab') {
            checkImage(scrollCon[0], 164, 164);
        }
        else {
            checkImage(scrollCon[0], 60, 60);
        }
    }


    var serCon = document.getElementsByClassName("content ser");
    if (serCon != null && serCon.length > 0) {
        if (serCon[0].parentNode.className == 'rtab') {
            checkImage(serCon[0], 164, 164);
        }
        else {
            checkImage(serCon[0], 60, 60);
        }
    }
}


function getDirection(sId) {
    var getA = document.getElementById("getAvalue").getElementsByTagName("a");
    for (var i = 0; i < getA.length; i++) {
        if (getA[i].id == sId) {
            getA[i].style.color = "#1770ae";
            getA[i].style.fontWeight = "bold";
            $("txt_ScrollDirection").setAttribute("value", getA[i].id);
        }
        else {
            getA[i].removeAttribute("style");
        }
    }
}

function getForm(sId, inputId) {
    var getS = $(sId);
    $(inputId).setAttribute("value", getS.value);
}

function changeLineRadio(rId) {
    switch (rId) {
        case "firstone":
            $("direction").show();
            $("forms").show();
            //$("directionLine2").hide();
            break;
        case "second":
            // $("directionLine2").show();
            $("direction").hide();
            $("forms").hide();
            break;
        case "nocheck":
            $("direction").hide();
            $("forms").hide();
            // $("directionLine2").hide();
            break;
        case "top":
            $("forms").hide();
            break;
        case "bottom":
            $("forms").hide();
            break;
        case "left":
            $("forms").show();
            break;
        case "right":
            $("forms").show();
        default:
            break;
    }
}

function scrolltopjian() {
    new Marquee("scroll_companyProduct", 0, 2, 740, 215, 30, 3000, 500)
}
function scrollcontinuleft() {
    new Marquee("scroll_companyProduct", 2, 1, 740, 215, 20, 0, 1000)
}
function scrollleftonebyone() {
    new Marquee(
{
    MSClass: ["scroll_all", "scroll_companyProduct"],
    Direction: 2,
    Step: 0.3,
    Width: 740,
    Height: 216,
    Timer: 20,
    DelayTime: 3000,
    WaitTime: 0,
    ScrollStep: 184,
    SwitchType: 0,
    AutoStart: true
});
}
function scrollLeftAll() {
    new Marquee(
{
    MSClass: ["scroll_all", "scroll_companyProduct"],
    Direction: 2,
    Step: 0.3,
    Width: 740,
    Height: 216,
    Timer: 20,
    DelayTime: 3000,
    WaitTime: 0,
    ScrollStep: 736,
    SwitchType: 0,
    AutoStart: true
});
}
function scrollrightcontinu() {
    new Marquee("scroll_companyProduct", 3, 1, 740, 216, 20, 0, 0)
}
function scrollRightOne() {
    new Marquee(
{
    MSClass: ["scroll_all", "scroll_companyProduct"],
    Direction: 3,
    Step: 0.3,
    Width: 740,
    Height: 216,
    Timer: 20,
    DelayTime: 3000,
    WaitTime: 0,
    ScrollStep: 184,
    SwitchType: 0,
    AutoStart: true
});
}
function scrollRightAll() {
    new Marquee(
{
    MSClass: ["scroll_all", "scroll_companyProduct"],
    Direction: 3,
    Step: 0.3,
    Width: 740,
    Height: 216,
    Timer: 20,
    DelayTime: 3000,
    WaitTime: 0,
    ScrollStep: 736,
    SwitchType: 0,
    AutoStart: true
});
}
function scrollBottom() {
    new Marquee("scroll_companyProduct", 1, 2, 740, 215, 30, 3000, 500)
}

function changeCompanyProductTitle(FontId, firstLine, radio_id1, raido_id2) {
    switch (FontId) {
        case radio_id1:
            $(firstLine).show();
            break;
        case raido_id2:
            $(firstLine).hide();
            break;
    }
}


function scrollAlbumleftR() {
    new Marquee("scrollAlbum", 2, 1, 720, 415, 20, 0, 1000)
}

function scrollAlbumleftL() {
    new Marquee("scrollAlbum", 2, 1, 215, 215, 20, 0, 1000)
}


function onloadEditor() {
    var options = {
        minHeight: "250px",
        uploadJson: "http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/UniversalFileUpload.ashx",
        filterMode: true
    };
    var editor = KindEditor.create('textarea[name="kindEditContent"]', options);
}

function getContent() {
    KindEditor.sync("#kindEditContent");
    var html = toUN.on(document.getElementById('kindEditContent').value);
    var inputContent = $("txt_CustomContent");
    inputContent.writeAttribute("value", html);
}
var toUN = {
    on: function (str) {
        var a = [],
        i = 0;
        for (; i < str.length; ) {
            a[i] = ("00" + str.charCodeAt(i++).toString(16)).slice(-4);

        }
        return "\\u" + a.join("\\u")
    },
    un: function (str) {
        return unescape(str.replace(/\\/g, "%"))
    }
}

function addNewBanner(element) {

    var parentElement = $(element).up("td");
    var elementList = parentElement.childElements();
    var _count = 0;

    for (var i = 0; i < elementList.length; i++) {
        if (elementList[i].readAttribute("class") == "lbotu mr10" || elementList[i].readAttribute("class") == "lbotu mr10 lbotu_check") {

            if (elementList[i].style.display !== "none") {

                _count = i + 1;
            }
        }
    }

    var _newcount = _count + 1;
    var url = "http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/AddOrDeleteNewBnner.ashx";
    var _selectby = "elementCount=" + _newcount;
    var myAjax = new Ajax.Request(url, { method: "get",
        parameters: _selectby,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {
                switch (_count) {
                    case 0:
                        elementList[0].style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case 1:
                        elementList[1].style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case 2:
                        elementList[2].style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case 3:
                        elementList[3].style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case 4:
                        elementList[4].style.display = "";
                        elementList[5].style.display = "none";
                        $("content_img").style.display = "";
                        break;
                    default:
                        break;

                }
            }
            else {
                alert("添加失败！");
            }
        }, asynchronous: false
    });
}

function deleteBannerImg(element) {
    var current = document.getElementById("select").value;
    var url = "http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/DeleteBannerImg.ashx";
    var myAjax = new Ajax.Request(url, { method: "get",
        parameters: "Sort=" + current,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText !== "") {
                var arr = req.responseText.evalJSON();
                var arr1 = arr.count;
                var arr2 = arr.model;
                switch (arr1) {
                    case "1":
                        $("img_1").style.display = "";
                        $("img_1").className = "lbotu mr10 lbotu_check"
                        $("img_2").style.display = "none";
                        $("img_3").style.display = "none";
                        $("img_4").style.display = "none";
                        $("img_5").style.display = "none";
                        $("img_6").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "2":
                        $("img_1").style.display = "";
                        $("img_1").className = "lbotu mr10 lbotu_check"
                        $("img_2").style.display = "";
                        $("img_3").style.display = "none";
                        $("img_4").style.display = "none";
                        $("img_5").style.display = "none";
                        $("img_6").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "3":
                        $("img_1").style.display = "";
                        $("img_1").className = "lbotu mr10 lbotu_check"
                        $("img_2").style.display = "";
                        $("img_3").style.display = "";
                        $("img_4").style.display = "none";
                        $("img_5").style.display = "none";
                        $("img_6").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "4":
                        $("img_1").style.display = "";
                        $("img_1").className = "lbotu mr10 lbotu_check"
                        $("img_2").style.display = "";
                        $("img_3").style.display = "";
                        $("img_4").style.display = "";
                        $("img_5").style.display = "none";
                        $("img_6").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "5":
                        $("img_1").style.display = "";
                        $("img_1").className = "lbotu mr10 lbotu_check"
                        $("img_2").style.display = "";
                        $("img_3").style.display = "";
                        $("img_4").style.display = "";
                        $("img_5").style.display = "";
                        $("content_img").style.display = "";
                        $("img_6").style.display = "none";
                        break;
                    default:
                        break;

                }

            }
            else {
                alert("删除失败！");
            }
        }, asynchronous: false
    });
}

function OnloadBannerImgMessage() {
    var url = "http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/GetFirstMessage.ashx";
    var myAjax = new Ajax.Request(url, { method: "get",
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText !== "") {
                var arr = req.responseText.evalJSON();
                var arr1 = arr.count;
                var arr2 = arr.model;
                switch (arr1) {
                    case "1":
                        $("img_1").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "2":
                        $("img_1").style.display = "";
                        $("img_2").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "3":
                        $("img_1").style.display = "";
                        $("img_2").style.display = "";
                        $("img_3").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "4":
                        $("img_1").style.display = "";
                        $("img_2").style.display = "";
                        $("img_3").style.display = "";
                        $("img_4").style.display = "";
                        $("content_img").style.display = "";
                        break;
                    case "5":
                        $("img_1").style.display = "";
                        $("img_2").style.display = "";
                        $("img_3").style.display = "";
                        $("img_4").style.display = "";
                        $("img_5").style.display = "";
                        $("content_img").style.display = "";
                        $("img_6").style.display = "none";
                        break;
                    default:
                        break;

                }
                $("img_1").addClassName("lbotu_check");
            }
        }, asynchronous: false
    });
}

function changeTab(element) {
    var id = $(element).readAttribute("id").replace("img_", "");
    var _id = "Sort=" + id;
    var url = "http://ui.tiantis.com/Scripts/MShopDec/AJAXpage/GetScrollImg.ashx";
    $(element).up().down(".lbotu_check").removeClassName("lbotu_check");
    $(element).addClassName("lbotu_check");
    var myAjax = new Ajax.Request(url, { method: "get",
        parameters: _id,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            var strJson = req.responseText.evalJSON();
            var obj = $("select");
            document.getElementById("select").value = strJson.Sort;
        }, asynchronous: false
    });
}
function scrollTab() {
    new Marquee(
    {
        MSClass: ["contentS", "scrollC"],
        Direction: 2,
        Step: 0.1,
        Width: 990,
        Height: 300,
        Timer: 20,
        DelayTime: 3000,
        WaitTime: 0,
        ScrollStep: 990,
        SwitchType: 0,
        AutoStart: true
    });
}


function onloadTemplateList() {
    var strCookie = document.cookie;
    var arrCookie = strCookie.split("; ");
    var userId;
    //遍历cookie数组，处理每个cookie对
    for (var i = 0; i < arrCookie.length; i++) {
        var arr = arrCookie[i].split("=");
        //找到名称为userId的cookie，并返回它的值
        if ("UserId" == arr[0]) {
            userId = arr[1];
            break;
        }
    }
    var getOnloadCount = function (result) {
        if (result == "0" || result == "2") {
            onloadFree();
            displayNone("x22");
            $("u2").innerHTML = '<img src="pf3.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf3.jpg*/>';
            $("t22").style.display = "block";
            displayNone("x33");
            $("u3").innerHTML = '<img src="pf1.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf1.jpg*/>';
            $("t33").style.display = "block";
            displayNone("x44");
            $("u4").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t44").style.display = "block";
            displayNone("x55");
            $("u5").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t55").style.display = "block";
        }
        else if (result == "1") {
            onloadFree();
            onloadYz();
            displayNone("x33");
            $("u3").innerHTML = '<img src="pf1.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf1.jpg*/>';
            $("t33").style.display = "block";
            displayNone("x44");
            $("u4").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t44").style.display = "block";
            displayNone("x55");
            $("u5").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t55").style.display = "block";
        }
        else if (result == "3") {
            onloadFree();
            onloadYz();
            onloadTgb();
            displayNone("x44");
            $("u4").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t44").style.display = "block";
            displayNone("x55");
            $("u5").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t55").style.display = "block";
        }
        else if (result == "4") {
            onloadFree();
            onloadYz();
            onloadTgb();
            onloadSyb();
            displayNone("x55");
            $("u5").innerHTML = '<img src="pf2.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/pf2.jpg*/>';
            $("t55").style.display = "block";
        }
        else if (result == "5") {
            onloadFree();
            onloadYz();
            onloadTgb();
            onloadSyb();
            onloadHg();
        }
        else if (result == "6") {
            onloadFree();
            onloadYz();
            onloadTgb();
            onloadSyb();
            onloadHg();
        }
    }
    isPayOrFree(userId, function (result) {
        getOnloadCount(result);
    });
}

function changeDiv2(id) {
    $("x11").style.display = "none";
    $("x22").style.display = "none";
    $("x33").style.display = "none";
    $("x44").style.display = "none";
    $("x55").style.display = "none";
    $(id).style.display = "block";
}
function mouseoutStyle(no) {
    $("b11").className = "bg02";
    $("b22").className = "bg02";
    $("b33").className = "bg02";
    $("b44").className = "bg02";
    $("b55").className = "bg02";
    $(no).className = "bg01";
}

//免费
function onloadFree() {
    var eles = document.getElementById("x11").getElementsByTagName("li");
    for (var i = 1; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}
function onloadYz() {
    var eles = document.getElementById("x22").getElementsByTagName("li");
    for (var i = 4; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}
function onloadTgb() {
    var eles = document.getElementById("x33").getElementsByTagName("li");
    for (var i = 10; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}
function onloadSyb() {
    var eles = document.getElementById("x44").getElementsByTagName("li");
    for (var i = 15; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}
function onloadHg() {
    var eles = document.getElementById("x55").getElementsByTagName("li");
    for (var i = 15; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}
function onloadBd() {
    var eles = document.getElementById("x55").getElementsByTagName("li");
    for (var i = 15; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}
function displayNone(eleid) {
    var eles = document.getElementById(eleid).getElementsByTagName("li");
    for (var i = 0; i < eles.length; i++) {
        eles[i].style.display = "none";
    }
}

function changeTab(id) {
    $("top_style").style.display = "none";
    $("top_navigation").style.display = "none";
    $("top_back").style.display = "none";
    $(id).style.display = "block";
}
function changeClass(no) {
    $("c11").className = "";
    $("c22").className = "";
    $("c33").className = "";
    $(no).className = "current";
}

function changeStyle(id, no) {
    $("nav_1").style.display = "none";
    $("nav_2").style.display = "none";
    $(id).style.display = "block";
    $("nav").className = "";
    $("col").className = "";
    $(no).className = "layerhover";
}
function changeTemp(id, no) {
    $("backList").style.display = "none";
    $("tempList").style.display = "none";
    $(id).style.display = "block";
    $("mytemp").className = "";
    $("templist").className = "";
    $(no).className = "layerhover";
}

function splitAppMode() {
    //var _json = "{\"ColName\":\"" + controlname + "\"";
    var _json = "{\"ColName\":\"Col_AppMode\",\"AppModeList\":[";
    var arrOld = document.getElementsByName("txt_OldName");
    var arrName = document.getElementsByName("txt_Name");
    var arrSort = document.getElementsByName("txt_Sort");
    var arrIsShow = document.getElementsByName("check_isshow");
    var arrUrl = document.getElementsByName("txt_Url");
    var arrImgUrl = document.getElementsByName("txt_ImgUrl");
    var resultJson = '';
    for (var i = 0; i < 6; i++) {
        var txtold = arrOld[i].value;
        var txtname = arrName[i].value;
        var txtsort = arrSort[i].value;
        var txtimgurl=arrImgUrl[i].value;
        if (arrIsShow[i].checked) {
            var txtisshow = 1;
        }
        else {
            var txtisshow = 0;
        }
        var txturl = arrUrl[i].value;
        switch (txtold) {
            case "关于我们":
                var txtimg = $("ImgAbout").value;
                break;
            case "产品展示":
                var txtimg = $("ImgProduct").value;
                break;
            case "服务列表":
                var txtimg = $("ImgService").value;
                break;
            case "新闻列表":
                var txtimg = $("ImgNews").value;
                break;
            case "联系我们":
                var txtimg = $("ImgContact").value;
                break;
            case "留言板":
                var txtimg = $("ImgLeaveMessage").value;
                break;
            default: break;
        }
        
        resultJson += "{\"OldName\":\"" + txtold + "\",\"Name\":\"" + txtname + "\",\"Url\":\"" + txturl + "\",\"Sort\":\"" + txtsort + "\",\"IsShow\":\"" + txtisshow + "\",\"ImgUrl\":\"" + txtimgurl + "\",\"NewImgUrl\":\""+txtimg+"\"},";

    }
    resultJson = resultJson.substring(0, resultJson.length - 1);
    _json += resultJson + "]}";
    return _json;
}
function updateAppMode(controlname) {
    var url = '/AJAXpage/UpdateApp';
    var _par = 'JsonContent=' + splitAppMode() + "&ColName=" + controlname;
    var myAjax = new Ajax.Request(url, { method: 'post',
        parameters: _par,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {   //成功！
                WinClose();
                subsave(controlname);
            } else {   //失败
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}

function returnAppMode(controlname,currentid) {
    var oldname = $(currentid).up(0).readAttribute("value");
    var url = 'AJAXpage/ReturnApp';
    var _par = 'ColName=' + controlname + '&OldName=' + oldname;
    var myAjax = new Ajax.Request(url, {
        method: 'post',
        parameters: _par,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {   //成功！
                $(currentid).up(0).previous(0).innerHTML = "NULL！";
                $(currentid).up(0).innerHTML = "一键还原";
                if (oldname=="关于我们") {
                    $("ImgAbout").value="";
                }else if (oldname=="产品展示") {
                    $("ImgProduct").value="";
                }else if (oldname=="服务列表") {
                    $("ImgService").value="";
                }else if (oldname=="新闻列表") {
                    $("ImgNews").value="";
                }else if (oldname=="联系我们") {
                    $("ImgContact").value="";
                }else if (oldname=="留言板") {
                    $("ImgLeaveMessage").value="";
                }
            } else {   //失败
                alert("操作失败！");
            }
        },asynchronous:false
    });
}


function splitNavColumn() {
    var _json = "{\"ControlName\":\"Col_NavColumn\",\"NavColumnList\":[";
    var arrOld = document.getElementsByName("txt_OldName");
    var arrName = document.getElementsByName("txt_Name");
    var arrSort = document.getElementsByName("txt_Sort");
    var arrIsShow = document.getElementsByName("check_isshow");
    var arrUrl = document.getElementsByName("txt_Url");
    var resultJson = '';
    for (var i = 0; i < arrOld.length; i++) {
        var txtold = arrOld[i].value;
        var txtname = arrName[i].value;
        var txtsort = arrSort[i].value;
        if (arrIsShow[i].checked) {
            var txtisshow = 1;
        }
        else {
            var txtisshow = 0;
        }
        var txturl = arrUrl[i].value;
        resultJson += "{\"OldName\":\"" + txtold + "\",\"Name\":\"" + txtname + "\",\"Url\":\"" + txturl + "\",\"Sort\":\"" + txtsort + "\",\"IsShow\":\"" + txtisshow + "\"},";

    }
    resultJson = resultJson.substring(0, resultJson.length - 1);
    _json += resultJson + "]}";
    return _json;
}

function updateNavColumn(controlname) {
    debugger;
    var url = '/AJAXpage/UpdateNavColumn';
    var _par = "JsonContent=" + splitNavColumn() + "&ColName=" + controlname;
    var myAjax = new Ajax.Request(url, { method: 'post',
        parameters: _par,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {   //成功！
                WinClose();
                subsave(controlname);
            } else {   //失败
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}


function changeNavTop(id, no) {
    $("navigationlist").style.display = "none";
    $("isshownav").style.display = "none";
    $(id).style.display = "block";
    $("navtop").className = "";
    $("isshowtop").className = "";
    $(no).className = "layerhover";
}



function changeIsShow(txtname, controlname) {
    var radvalue = splitJsonContent(txtname);
    var par = "json=" + radvalue;
    var url = '/AJAXpage/ChangeIsShow';
    var myAjax = new Ajax.Request(url, { method: 'post',
        parameters: par,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {   //成功！
                subsave(controlname);
            } else {   //失败
                alert("操作失败！")
            }
        }, asynchronous: false
    });
}


function splitSquared() {
    var _json = "{\"ColName\":\"Col_Squared\",\"SquaredList\":[";
    var arrOld = document.getElementsByName("txt_OldName");
    var arrName = document.getElementsByName("txt_Name");
    var arrSort = document.getElementsByName("txt_Sort");
    var arrIsShow = document.getElementsByName("check_isshow");
    var arrUrl = document.getElementsByName("txt_Url");
    var arrImgUrl = document.getElementsByName("txt_ImgUrl");
    var arrColorid = document.getElementsByName("txt_ColorId");
    var resultJson = '';
    for (var i = 0; i < 6; i++) {
        var txtold = arrOld[i].value;
        var txtname = arrName[i].value;
        var txtsort = arrSort[i].value;
        if (arrIsShow[i].checked) {
            var txtisshow = 1;
        }
        else {
            var txtisshow = 0;
        }
        var txturl = arrUrl[i].value;
        var txtimg = arrImgUrl[i].value;
        var txtcolorid = arrColorid[i].value;
        resultJson += "{\"OldName\":\"" + txtold + "\",\"Name\":\"" + txtname + "\",\"Url\":\"" + txturl + "\",\"Sort\":\"" + txtsort + "\",\"IsShow\":\"" + txtisshow + "\",\"ImgUrl\":\"" + txtimg + "\",\"ColorId\":\"" + txtcolorid + "\"},";

    }
    resultJson = resultJson.substring(0, resultJson.length - 1);
    _json += resultJson + "]}";
    return _json;
}
function updateSquared(controlname) {
    var url = '/AJAXpage/UpdateSqu';
    var _par = 'JsonContent=' + splitSquared() + "&ColName=" + controlname;
    var myAjax = new Ajax.Request(url, { method: 'post',
        parameters: _par,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "1") {   //成功！
                WinClose();
                subsave(controlname);
            } else {   //失败
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}



// 计算对象居中需要设置的left和top值  
// 参数：  
//  _w - 对象的宽度  
//  _h - 对象的高度  
function getLT(_w, _h) {
    var de = document.documentElement;
    // 获取当前浏览器窗口的宽度和高度  
    // 兼容写法，可兼容ie,ff  
    var w = self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
    var h = (de && de.clientHeight) || document.body.clientHeight;

    // 获取当前滚动条的位置  
    // 兼容写法，可兼容ie,ff  
    var st = (de && de.scrollTop) || document.body.scrollTop;

    var topp = 0;
    if (h > _h) topp = (st + (h - _h) / 2);
    else topp = st;

    var leftp = 0;
    if (w > _w) leftp = ((w - _w) / 2);

    // 左侧距，顶部距  
    return [leftp, topp];
}
function showShare(areaid) {
    document.getElementById(areaid + "bg").style.display = "block";
    document.getElementById(areaid).style.display = "block";
};
function hideShare(areaid) {
    document.getElementById(areaid).style.display = "none";
    document.getElementById(areaid + "bg").style.display = "none";
};
function initShare() {
    this.init = function (_shareid) {
        var shareid = _shareid;
        var body = document.getElementById("body" + shareid).value;
        document.getElementById("email" + shareid).href = "mailto:?subject=&body=" + encodeURIComponent(body);
        document.getElementById("weibo" + shareid).href = "http://service.weibo.com/share/share.php?&title=" + encodeURIComponent(body);
        document.getElementById("tx_weibo" + shareid).href = "http://share.v.t.qq.com/index.php?c=share&a=index&title=" + encodeURIComponent(body);
        document.getElementById("sms" + shareid).onclick = function () {
            hideShare("share" + _shareid);
            var form = document.getElementById("form" + shareid);
            form.setAttribute("action", "sms:");
            form.submit();
        }
    }
}

function deleteImg(element) {
    var url = '/AJAXpage/DeleteImg';
    var _par = 'imgId=' + element;
    var myAjax = new Ajax.Request(url, { method: 'post',
        parameters: _par,
        requestHeaders: ['Cache-Control', 'no-cache', 'If-Modified-Since', '0'],
        onComplete: function (req) {
            if (req.responseText == "OneDel") {
                $("fileNameOne").innerHTML = "null";
                $("fileTypeOne").innerHTML = "未上传";
                $("txt_ImgurlOne").value = "";
            } else if (req.responseText == "TwoDel") {
                $("fileNameTwo").innerHTML = "null";
                $("fileTypeTwo").innerHTML = "未上传";
                $("txt_ImgurlTwo").value = "";
            } else if (req.responseText == "ThreeDel") {
                $("fileNameThree").innerHTML = "null";
                $("fileTypeThree").innerHTML = "未上传";
                $("txt_ImgurlThree").value = "";
            } else {
                alert("操作失败！");
            }
        }, asynchronous: false
    });
}