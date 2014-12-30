
window.onload = function () {
    initPbPageStyle("doc", "bottomContent");
    initPbUserLayoutStyle("bottomContent");
}

function setCustomColStyle(colId) {
    var obj = getPbInitColUserStyle(colId);
    initPbColStyle(obj, colId);
}

function setCustomColRightStyle(colId) {
    var obj = getPbInitColUserRightStyle(colId);
    initPbColStyle(obj, colId);
}

document.getElementsByClassName = function () {
    var tTagName = "*";
    if (arguments.length > 1) {
        tTagName = arguments[1];
    }
    if (arguments.length > 2) {
        var pObj = arguments[2]
    }
    else {
        var pObj = document;
    }
    var objArr = pObj.getElementsByTagName(tTagName);
    var tRObj = new Array();
    for (var i = 0; i < objArr.length; i++) {
        if (objArr[i].className == arguments[0]) {
            tRObj.push(objArr[i]);
        }
    }
    return tRObj;
}

function setCustomIPColTitleSytle(colId) {
    var obj = getPbInitColUserStyle(colId);
    initIPPbColTitleStyle(obj, colId);
}

function initIPPbColTitleStyle(obj, colid) {
    if (obj != null && typeof (obj.IsDefault) != "undefined" && obj.IsDefault != null) {
        if (obj.IsDefault != 1) {
            if (typeof (obj.TitleBgColor) != "undefined" && obj.TitleBgColor != null) {

                setIPPbColTitleStyle(obj.TitleBgColor, "ControlStyle.TitleBgColor");
            }
            if (typeof (obj.TitleBgImage) != "undefined") {
                setIPPbColTitleStyle(obj.TitleBgImage, "ControlStyle.TitleBgImage");
            }
            if (typeof (obj.TitleTextColor) != "undefined" && obj.TitleTextColor != null) {
                setIPPbColTitleStyle(obj.TitleTextColor, "ControlStyle.TitleTextColor");
            }
        }
    }
}

function setIPPbColTitleStyle(colValue, colName) {
    var colList = document.getElementsByClassName("maintit");
    var setType = colName.split(".")[1]
    for (i = 0; i < colList.length; i++) {
        if (colList[i] != null) {
            var col = colList[i];
            switch (setType) {
                case "TitleBgColor":
                    col.style.backgroundColor = colValue;
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
}

function setCustomNavStyle(colId) {
    var obj = getPbInitColUserStyle(colId);
    initPbNavigationListStyle(obj)
}

function getPbInitColUserStyle(obj) {
    var doc = document.getElementById(obj);
    var robj;
    if (typeof (doc) != "undefined" && doc != null) {
        var str = doc.getAttribute("config-style-data");
        if (str != "")
            robj = JSON.parse(str);
    }
    return robj;
}

function getPbInitColUserRightStyle(obj) {
    var doc = document.getElementById(obj);
    if (typeof (doc) != "undefined" && doc != null) {
        var str = doc.getAttribute("config-colstyle-data");
        var obj = JSON.parse(str);
    }
    return obj;
}

function getPbInitUserLayoutStyle(obj) {
    var doc = document.getElementById(obj);
    if (typeof (doc) != "undefined" && doc != null) {
        var str = doc.getAttribute("config-Layout-data");
        var obj = JSON.parse(str);
    }
    return obj;
}

function initPbNavigationListStyle(obj) {
    if (obj != null && typeof (obj.IsDefault) != "undefined" && obj.IsDefault != null) {
        if (obj.IsDefault != 1) {
            if (typeof (obj.HoverBgColor) != "undefined" && obj.HoverBgColor != null) {
                setPbNavModifyStyle(obj.HoverBgColor, "NavigationListStyle.HoverBgColor");
            }
            if (typeof (obj.HoverBgImage) != "undefined" && obj.HoverBgImage != null) {
                setPbNavModifyStyle(obj.HoverBgImage, "NavigationListStyle.HoverBgImage");
            }
            if (typeof (obj.HoverTextColor) != "undefined" && obj.HoverTextColor != null) {
                setPbNavModifyStyle(obj.HoverTextColor, "NavigationListStyle.HoverTextColor");
            }

            if (typeof (obj.BgColor) != "undefined" && obj.BgColor != null) {
                setPbNavDefaultStyle(obj.BgColor, "NavigationListStyle.BgColor");
            }
            if (typeof (obj.BgImage) != "undefined" && obj.BgImage != null) {
                setPbNavDefaultStyle(obj.BgImage, "NavigationListStyle.BgImage");
            }
            if (typeof (obj.TextColor) != "undefined" && obj.TextColor != null) {
                setPbNavDefaultStyle(obj.TextColor, "NavigationListStyle.TextColor");
            }
        }
    }
}


//function setNavLink() {
//    var currentDomain = document.domain;
//    if (currentDomain != "http://ui.tiantis.com/Scripts/MShopDec/zx.qihuiwang.com") {
//        var getA = document.getElementById("menu").getElementsByTagName("a");
//        for (var i = 0; i < getA.length; i++) {
//            var getAsort = getA[i].getAttribute("id").replace("spNav", "");
//            var getInput = document.getElementsByName("txt_Url");
//            for (var y = 0; y < getInput.length; y++) {
//                var getInputSort = getInput[i].getAttribute("sort");
//                if (getAsort == getInputSort) {
//                    var getInputValue = getInput[i].getAttribute("value");
//                    getA[i].href = getInputValue;
//                    break;
//                }
//            }
//        }
//    }
//}

function setPbNavDefaultStyle(colValue, colName) {
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
                var txt_NavTextColor = document.getElementById("txt_NavTextColor");
                txt_NavTextColor.value = colValue;
                col.style.color = colValue;
                break;
            default:
                break;
        }
    }
}

function setPbNavModifyStyle(colValue, colName) {
    var colList = document.getElementById("menu").getElementsByTagName("a");
    var navStyle = colName.split('.')[1];
    switch (navStyle) {
        case "HoverBgColor":
            var txt_NavHoverBgColor = document.getElementById("txt_NavHoverBgColor");
            txt_NavHoverBgColor.value = colValue;
            break;
        case "HoverBgImage":
            var txt_NavHoverBgImage = document.getElementById("txt_NavHoverBgImage");
            txt_NavHoverBgImage.value = colValue;
            break;
        case "HoverTextColor":
            var txt_NavHoverTextColor = document.getElementById("txt_NavHoverTextColor");
            txt_NavHoverTextColor.value = colValue;
            break;
        default:
            break;
    }
    for (var i = 0; i < colList.length; i++) {
        var col = colList[i];
        col.onmouseover = function () {
            var colNavBgColor = document.getElementById("colNavModifyBgColor")
            if (colNavBgColor != null) {
                this.style.backgroundColor = colNavBgColor.style.backgroundColor;
            }
            else {
                var txt_NavHoverBgColor = document.getElementById("txt_NavHoverBgColor");
                this.style.backgroundColor = txt_NavHoverBgColor.value;
            }

            var colNavTextColor = document.getElementById("colNavModifyTextColor")
            if (colNavTextColor != null) {
                this.style.color = colNavTextColor.style.backgroundColor;
            }
            else {
                var txt_NavHoverTextColor = document.getElementById("txt_NavHoverTextColor");
                this.style.color = txt_NavHoverTextColor.value;
            }

            var colNavBgImage = document.getElementById("txt_NavListHoverImgurl")
            if (colNavBgImage != null) {
                this.style.backgroundImage = "url(" + colNavBgImage.value + ")";
            }
            else {
                var txt_NavHoverBgImage = document.getElementById("txt_NavHoverBgImage");
                this.style.backgroundImage = "url(" + txt_NavHoverBgImage.value + ")";
            }
        }
        col.onmouseout = function () {
            this.style.background = "";

            var colFontColor = document.getElementById("colNavFontColor")
            if (colFontColor != null) {
                this.style.color = colFontColor.style.backgroundColor;
            }
            else {
                var txt_NavTextColor = document.getElementById("txt_NavTextColor");
                this.style.color = txt_NavTextColor.value;
            }
        }
    }
}

function initPbColStyle(obj, colId) {
    if (obj != null && typeof (obj.IsDefault) != "undefined" && obj.IsDefault != null) {
        if (obj.IsDefault != 1) {
            if (typeof (obj.TitleBgColor) != "undefined" && obj.TitleBgColor != null) {
                setPbColTitleStyle(obj.TitleBgColor, "ControlStyle.TitleBgColor", colId);
            }
            if (typeof (obj.TitleBgImage) != "undefined") {
                setPbColTitleStyle(obj.TitleBgImage, "ControlStyle.TitleBgImage", colId);
            }
            if (typeof (obj.TitleTextColor) != "undefined" && obj.TitleTextColor != null) {
                setPbColTitleStyle(obj.TitleTextColor, "ControlStyle.TitleTextColor", colId);
            }

            if (typeof (obj.IsContentBorder) != "undefined" && obj.IsContentBorder != null) {
                setPbColContentStyle(obj.IsContentBorder, "ControlStyle.IsContentBorder", colId);
            }
            if (typeof (obj.ContentBorderColor) != "undefined" && obj.ContentBorderColor != null) {
                setPbColContentStyle(obj.ContentBorderColor, "ControlStyle.ContentBorderColor", colId);
            }
            if (typeof (obj.ContentOpacity) != "undefined" && obj.ContentOpacity != null) {
                setPbColContentStyle(obj.ContentOpacity, "ControlStyle.ContentOpacity", colId);
            }
            if (typeof (obj.ContentTextColor) != "undefined" && obj.ContentTextColor != null) {
                setPbColContentStyle(obj.ContentTextColor, "ControlStyle.ContentTextColor", colId);
            }
            if (typeof (obj.ContentLinkColor) != "undefined" && obj.ContentLinkColor != null) {
                setPbColContentStyle(obj.ContentLinkColor, "ControlStyle.ContentLinkColor", colId);
            }
        }
    }
}

function setPbColTitleStyle(colValue, colName, colId) {
    var rootCol = document.getElementById(colId);
    if (typeof (rootCol) != "undefined" && rootCol != null) {
        var colList = document.getElementsByClassName("title");
        var setType = colName.split(".")[1];
        for (i = 0; i < colList.length; i++) {
            if (colList[i] != null) {
                var col = colList[i];
                switch (setType) {
                    case "TitleBgColor":
                        col.style.backgroundColor = colValue;
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
    }
}

function setPbColContentStyle(colValue, colName, colId) {
    var rootCol = document.getElementById(colId);
    var setType = colName.split(".")[1];
    var colList = document.getElementsByClassName("content_box");
    if (colList[0] != null) {
        var col = colList[0];
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

function initPbPageStyle(colId, bottomId) {

    var obj = getPbInitColUserStyle(bottomId);
    initPbBgStyle(obj, colId)

}

function initPbUserLayoutStyle(bottomId) {
    var obj = getPbInitUserLayoutStyle(bottomId)
    initPbPageLayout(obj)
}

function initPbBgStyle(obj, colId) {
    if (obj != null && typeof (obj.IsDefault) != "undefined" && obj.IsDefault != null) {

        if (obj.IsDefault != 1) {
            if (typeof (obj.BgColor) != "undefined" && obj.BgColor != null) {

                setPbBackgroundStyle(obj.BgColor, "PageBackground.BgColor", colId);
            }
            if (typeof (obj.BgImage) != "undefined") {
                setPbBackgroundStyle(obj.BgImage, "PageBackground.BgImage", colId);
            }
            if (typeof (obj.BgImagePosition) != "undefined" && obj.BgImagePosition != null) {
                setPbBackgroundStyle(obj.BgImagePosition, "PageBackground.BgImagePosition", colId);
            }
            if (typeof (obj.BgImageRepeat) != "undefined" && obj.BgImageRepeat != null) {
                setPbBackgroundStyle(obj.BgImageRepeat, "PageBackground.BgImageRepeat", colId);
            }
        }
    }
}

function setPbBackgroundStyle(colValue, colName, colId) {
    var colDoc = document.getElementById(colId);
    var setStyle = colName.split('.')[1];
    switch (setStyle) {
        case "BgColor":
            colDoc.style.backgroundColor = colValue;
            break;
        case "BgImage":
            colDoc.style.backgroundImage = "url(" + colValue + ")";
            break;
        case "BgImageRepeat":
            setPbBgImageRepeat(colValue, colDoc);
            break;
        case "BgImagePosition":
            switch (colValue) {
                case "top left":
                    colDoc.style.backgroundPosition = "top left";
                    break;
                case "top center":
                    colDoc.style.backgroundPosition = "top center";
                    break;
                case "top right":
                    colDoc.style.backgroundPosition = "top right";
                    break;
                default:
                    break;
            }
            break;
        default:
            break;
    }
}

function setPbBgImageRepeat(colValue, obj) {
    switch (colValue) {
        case "0":
            obj.style.backgroundRepeat = "repeat";
            break;
        case "1":
            obj.style.backgroundRepeat = "repeat-x";
            break;
        case "2":
            obj.style.backgroundRepeat = "repeat-y";
            break;
        case "3":
            obj.style.backgroundRepeat = "no-repeat";
            break;
        default:
            break;
    }
}

function initPbPageLayout(obj) {

    if (typeof (obj) != "undefined" && obj != null) {
        modifyPbPageLayout(obj, "EpageLayOut");
    }
}

function modifyPbPageLayout(colValue, colName) {

    var leftBox = document.getElementsByClassName('leftbox')[0];
    var rightBox = document.getElementsByClassName('rightbox')[0];
    if (typeof (leftBox) != "undefined" && leftBox != null && typeof (rightBox) != "undefined" && rightBox != null) {
        if (colValue == 0) {
            leftBox.style.cssFloat = leftBox.style.styleFloat = "left";
            rightBox.style.cssFloat = rightBox.style.styleFloat = "right";

            var leftComs = document.getElementsByClassName("lcom");
            if (leftComs != null && leftComs.length > 0) {
                var leftCom = leftComs[0];
                leftCom.style.cssFloat = leftCom.style.styleFloat = "left";
            }

            var rightInnerControls = document.getElementsByClassName("nrtab  mb20");
            for (var j = 0; j < rightInnerControls.length; j++) {
                var rightCol = rightInnerControls[j];
                rightCol.style.cssFloat = rightCol.style.styleFloat = "right";
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

            var leftComs = document.getElementsByClassName("lcom");
            if (leftComs != null && leftComs.length > 0) {
                var leftCom = leftComs[0];
                leftCom.style.cssFloat = leftCom.style.styleFloat = "right";
            }

            var rightInnerControls = document.getElementsByClassName("nrtab  mb20");
            for (var j = 0; j < rightInnerControls.length; j++) {
                var rightCol = rightInnerControls[j];
                rightCol.style.cssFloat = rightCol.style.styleFloat = "left";
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

/* Logo上字体属性设置 */
function displayMessage() {
    var isShowValue = document.getElementById("companyName").getAttribute("value");
    if (isShowValue == "0") {
        document.getElementById("companyName").style.display = "none";
    }
}

function hideBannerMessage() {
    var isShowValue = document.getElementById("messageforbanner").getAttribute("isshow");
    if (isShowValue == "0") {
        document.getElementById("messageforbanner").style.display = "none";
    }
}
function pbCheckImage(obj, w, h) {
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

function pbModifyAllImage() {
    pbleftModifyAllImage();
    pbrightModifyAllImage();
}

function pbleftModifyAllImage() {
    var picCon = document.getElementsByClassName("content pic");
    if (picCon != null && picCon.length > 0) {
        if (picCon[0].parentNode.className == 'ltab') {
            pbCheckImage(picCon[0], 60, 60);
        }
    }

    var honorCon = document.getElementsByClassName("content honor");
    if (honorCon != null && honorCon.length > 0) {
        if (honorCon[0].parentNode.className == 'ltab') {
            pbCheckImage(honorCon[0], 60, 60);
        }
    }

    var proCon = document.getElementsByClassName("content pro");
    if (proCon != null && proCon.length > 0) {
        if (proCon[0].parentNode.className == 'ltab') {
            pbCheckImage(proCon[0], 60, 60);
        }
    }

    var pscCon = document.getElementsByClassName("content psc");
    if (pscCon != null && pscCon.length > 0) {
        if (pscCon[0].parentNode.className == 'ltab') {
            pbCheckImage(pscCon[0], 60, 60);
        }
    }

    var serCon = document.getElementsByClassName("content ser");
    if (serCon != null && serCon.length > 0) {
        if (serCon[0].parentNode.className == 'ltab') {
            pbCheckImage(serCon[0], 60, 60);
        }
    }
}

function pbrightModifyAllImage() {
    var picCon = document.getElementsByClassName("pic");
    if (picCon != null && picCon.length > 0) {
        pbCheckImage(picCon[0], 164, 164);
    }

    var honorCon = document.getElementsByClassName("honor1");
    if (honorCon != null && honorCon.length > 0) {
        pbCheckImage(honorCon[0], 310, 310);
    }

    var proCon = document.getElementsByClassName("pro");
    if (proCon != null && proCon.length > 0) {
        pbCheckImage(proCon[0], 164, 164);
    }

    var pscCon = document.getElementsByClassName("psc");
    if (pscCon != null && pscCon.length > 0) {
        pbCheckImage(pscCon[0], 164, 164);
    }

    var serCon = document.getElementsByClassName("content ser");
    if (serCon != null && serCon.length > 0) {
        for (var i = 0; i < serCon.length; i++) {
            if (serCon[i].parentNode.className == 'nrtab  mb20') {
                pbCheckImage(serCon[i], 164, 164);
            }
        }
    }

    var serCon = document.getElementsByClassName("picshow1");
    if (serCon != null && serCon.length > 0) {
        pbCheckImage(serCon[i], 114, 114);
    }
}

function getJsonP() {
    var so = document.createElement('SCRIPT');
    so.src = 'CheckLoginStatus.ashx-callback=getJsonPuser'/*tpa=http://wp.qihuiwang.com/CommonAshx/CheckLoginStatus.ashx?callback=getJsonPuser*/;
    document.getElementById("jsonP_content").appendChild(so);
}
function getJsonPuser(user) {
    if (user != "") {
        document.getElementById("N_user").innerHTML = user.UserName;
        document.getElementById("T_user").innerHTML = '[<a href="http://wp.qihuiwang.com/CommonAshx/SignOut.ashx">退出</a>]';
        document.getElementById("D_user").innerHTML = "";
        document.getElementById("Z_user").innerHTML = "";
    }
    else {
        document.getElementById("T_user").innerHTML = "";
        document.getElementById("D_user").innerHTML = "[<a target=\"_blank\" href=\"http://wp.qihuiwang.com/login/\">请登录</a>]";
        document.getElementById("Z_user").innerHTML = '[<a target="_blank" href="http://wp.qihuiwang.com/register/">免费注册</a>]';
    }
}
function voluationContent() {
    document.getElementById("ser_product").innerHTML = "<a href=\"###\" target=\"_blank\" onclick=\"onclickSer(this);\">搜全站</a>";
}
function onclickSer(acontrol) {
    var txt_ser = document.getElementById("txt_Ser").value;
    //location.href = "http://www.qihuiwang.com/product/q0/?search=" + txt_ser;
    if (txt_ser != "") {
        acontrol.setAttribute("href", "http://www.qihuiwang.com/product/q0/?search=" + encodeURIComponent(txt_ser));
    }
    else {
        acontrol.setAttribute("href", "http://www.qihuiwang.com/product/q0");
    }
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

function SecProductCategoryControl(fatherid, imgC) {
    var imgsrc = imgC.src;
    var imgPath = imgsrc.substr(imgsrc.length - 7, 3);
    var father = document.getElementById(fatherid);
    var sons = document.getElementsByName("Sec" + fatherid);
    for (var i = 0; i < sons.length; i++) {
        var son = sons[i];
        if (imgPath == 'sub') {

            son.style.display = "none";
            imgC.src = "add.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/public/add.jpg*/
        }
        else {
            son.style.display = "block";
            imgC.src = "sub.jpg"/*tpa=http://ui.tiantis.com/Images/MShopDec/public/sub.jpg*/
        }
    }
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

