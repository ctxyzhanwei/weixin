window.onload = function() {
    var tableScroll = document.querySelector(".table-scroll");
    var containerTitle = document.querySelector(".container-title");
    var containerBody = document.querySelector(".container-body");
    var containerContent = document.querySelectorAll(".container-content");
    var btnStart = document.querySelector("#btn-start");
    var btnSubmit = document.querySelector(".btn-start");
    var btnCloseAd = document.querySelector(".btn-close-ad");
    var btnCancel = document.querySelector(".btn-cancel");
    var btnKnow = document.querySelector(".btn-know");
    var domAd = document.querySelector(".ads");
    var domTable = document.querySelector(".table-body tbody");
    var inputFile = document.querySelector(".input-file");
    var inputName = document.querySelector(".input-name");
    var iconImg = document.querySelector(".register-content .icon-img");
    var panelLayer = document.querySelector(".register-panel-layer");
    var panelBox = document.querySelector("#panel-register");
    var panelBox2 = document.querySelector("#panel-without-times");
    var tableTitle = document.querySelector(".table-title");
    var pageLoadingBox = document.querySelector(".page-loading-box");
    var containerActivityBox = document.querySelector(".container-activity-content");
//    var companyRightsHeight = document.querySelector(".company-rights").offsetHeight;
    var tableTitleHeight = tableTitle.offsetHeight;
    var tableTitleTop = tableTitle.offsetTop;
    var tableBottomHeight = document.querySelector(".table-bottom").offsetHeight;
    var bodyHeight = document.documentElement.clientHeight;
    var tableTRHeight = 0;
    var needTop = false;
    var bodyCanMove = true;
    var tableBody = document.querySelector(".table-scroll");


    window.URL = window.URL || window.webkitURL;

    containerTitle.onclick = function(e) {
        if (e.target.nodeName === "A") {
            switch (e.target.className) {
                case "btn-rank":
                    showContainerContent(0);
                    break;
                case "btn-activity":
                    showContainerContent(1);
                    break;
            }
        }
    }
/*
    btnSubmit.onclick = function(e) {
        var nick = document.querySelector("[name=input-name]");
        var tel = document.querySelector(".register-main input[type=tel]");
        if (tel.value == "" || nick == "") {
            e.preventDefault();
            if (nick.value == "") {
                panelBox2.querySelector(".plus").innerHTML = nick.placeholder + "!";
            } else {
                panelBox2.querySelector(".plus").innerHTML = tel.placeholder + "!";
            }
            panelBox.classList.remove("show");
            panelBox2.classList.add("show");
            panelLayer.classList.add("show");
        }
    }

    btnKnow.onclick = function() {
        panelBox2.classList.remove("show");
        panelLayer.classList.remove("show");
    }

    panelBox.ontouchmove = function(e) {
        e.preventDefault();
    }
    panelBox2.ontouchmove = function(e) {
        e.preventDefault();
    }
    panelLayer.ontouchmove = function(e) {
        e.preventDefault();
    }
*/
    btnStart.onclick = function() {
        if (!window.start_page_config.NEEDREGISTER) {
            if (window.start_page_config.RESTTIMES <= 0 || window.start_page_config.TOTALRESTTIMES <= 0) {
                if (window.start_page_config.TOTALRESTTIMES <= 0) {
                    panelBox2.querySelector(".plus").innerHTML = "你的游戏机会已经用完<br/>期待下次活动开启!";
                } else {
                    panelBox2.querySelector(".plus").innerHTML = "你的游戏机会已经用完<br/>明天再来吧!";
                }
                panelBox2.classList.add("show");
                panelLayer.classList.add("show");
                return;
            }
            window.location.href = window.start_page_config.PATH.GAME;
            return;
        }
        panelBox.classList.add("show");
        panelLayer.classList.add("show");
    }
	
	/*
    btnCancel.onclick = function() {
        panelBox.classList.remove("show");
        panelLayer.classList.remove("show");
        iconImg.src = iconImg.getAttribute("defaultsrc");
    }
    $(inputFile).localResizeIMG({
        width: 150,
        quality: 0.9,
        success: function(result) {
            iconImg.src = result.base64;
            document.querySelector("[name=input-file]").value = result.base64;
        }
    });

    if (btnCloseAd) {
        btnCloseAd.onclick = function() {
            domAd.classList.remove("show");
            if (needTop)
                calTableHeight(true);
            rankList.refresh();
        }
    }
	
	*/
    document.addEventListener('touchmove', function(e) {
        if (!bodyCanMove) {
            e.preventDefault();
        }
    }, false);
    var rankData = window.rankData;
    var showNum = 5;
    var loadNum = 10;
    var currentNum = 0;
    var totalNum = (window.start_page_config.RANKTOTALPAGE - 1) * loadNum;
    var topHeight = containerTitle.offsetTop;

    var descScroll = new iScroll(containerActivityBox, {
        onScrollStart: function() {
            bodyCanMove = false;
        },
        onTouchEnd: function() {
            bodyCanMove = true;
            if (descScroll.startY === 0 && descScroll.dirY < 0) {
                scrollToPosY.scrollToY(0);
            } else {
                scrollToPosY.scrollToY(topHeight);
            }
        }
    });

    var rankList = new iScroll(tableScroll, {
        onScrollStart: function() {
            bodyCanMove = false;
            if (currentNum === 0 && !needTop) {
                needTop = true;
                calTableHeight(true);
            }
        },
        onScrollMove: function() {
            removeLoadingText();
        },
        onTouchEnd: function() {
            bodyCanMove = true;
            //console.log("onTouchEnd:", rankList.dirY, rankList.startY);
            if (rankList.startY === 0 && rankList.dirY < 0) {
                scrollToPosY.scrollToY(0);
            } else {
                scrollToPosY.scrollToY(topHeight);
            }
        },
        onScrollEnd: function() {
            if (rankList.y <= 0.8 * rankList.maxScrollY && rankList.dirY > 0) {
                if (currentNum < totalNum) {
                    currentNum += loadNum;
                  /*   getRankData(function(data) {
                        createTableData(rankData);
                    }); */
                }
            }

        }
    });

    var scrollToPosY = (function() {
        var currentY = 0;
        var targetY = 0;
        var movePer = 0;
        var scrollHandler = 0;
        var direction = 1; // 1 :down  -1:up
        var times = 0;
        var currentTimes = 0;
        var timePer = 13;

        function startMove() {
            currentTimes++;
            if (currentTimes === times) {
                stopMove();
                return;
            }
            movePer = Math.ceil(Math.abs(targetY - currentY) / 20);
           // console.log(movePer);
            if (movePer <= 0) {
                stopMove();
                return;
            }

            currentY = currentY + movePer * direction;
            //console.log(currentY);
            window.scrollTo(0, currentY);

            scrollHandler = setTimeout(function() {
                startMove();
            }, timePer);
        }

        function stopMove() {
            clearTimeout(scrollHandler);
            scrollHandler = 0;
            currentTimes = 0;
            targetY = 0;
            currentY = 0;
            times = 0;
        }

        var main = {
            scrollToY: function(posY) {
                targetY = posY;
                currentY = window.scrollY;
                times = Math.abs(targetY - currentY) / timePer;
                direction = targetY > currentY ? 1 : -1;
                startMove();
            }
        };
        return main;
    })();

//    createTableData(rankData);
    removeLoadingText();

    function removeLoadingText() {
        if (currentNum >= totalNum && pageLoadingBox) {
            domTable.removeChild(pageLoadingBox);
            pageLoadingBox = null;
            rankList.refresh();
        }
    }

    function getRankData(callback) {
        var dataPara = {
            page: currentNum / loadNum
        }
        if (0) {
            var result = [{
                "img": "blackwhite_imgs/icon_head_template.png",
                "name": "Mia2",
                "score": 1.23
            }];
            rankData = rankData.concat(result);
            setTimeout(function() {
                typeof callback === "function" && callback();
            }, 1000);

            return;
        }


        $.ajax({
            url: window.start_page_config.URL.RANKDATA,
            type: "post",
            data: dataPara,
            success: function(result) {
                var data = JSON.parse(result);
                rankData = rankData.concat(data);
                typeof callback === "function" && callback(data);
            }
        });
    } 

    function calTableHeight(removeTop) {
/* 		alert(1);
        var minus = removeTop ? 0 : topHeight;
        var num = bodyHeight - (containerTitle.offsetHeight + tableTitleHeight + tableTitleTop + tableBottomHeight + minus);
        var num2 = num + tableTitleHeight + tableBottomHeight + tableTitleTop;
        tableScroll.style.height = num + "px";
        containerBody.style.height = num2 + "px"; */
    }
/*
    function createTableData(data) {
        var fragment = document.createDocumentFragment();
        for (var i = currentNum; i < currentNum + loadNum; i++) {
            if (data[i]) {
                var tr = document.createElement("tr");
                var trClass = "";
                var tdIndex = i + 1;
                switch (i) {
                    case 0:
                    case 1:
                    case 2:
                        trClass = "icon_rank rank" + (i + 1);
                        tdIndex = "";
                        break;
                }
                var td = document.createElement("td");
                tr.appendChild(td);

                var span = document.createElement("span");
                span.textContent = tdIndex;
                span.className = trClass;
                td.appendChild(span);

                var td = document.createElement("td");
                tr.appendChild(td);

                var span = document.createElement("span");
                span.className = "icon-img-bg";
                td.appendChild(span);

                var img = new Image();
                img.src = data[i]["img"];
                img.className = "icon-img";
                span.appendChild(img);

                var td = document.createElement("td");
                td.textContent = data[i]["name"];
                tr.appendChild(td);

                var td = document.createElement("td");
                td.textContent = data[i]["score"] + "''";
                tr.appendChild(td);

                fragment.appendChild(tr);
            }
        }
        domTable.insertBefore(fragment, domTable.lastChild);
        rankList.refresh();

        tableTRHeight = tableTRHeight > 0 ? tableTRHeight : tableBody.querySelector("tr").offsetHeight;
    }
*/
    function showContainerContent(index) {
        for (var i = 0; containerContent[i]; i++) {
            var content = containerContent[i];
            content.style.display = (i === index) ? "block" : "none";
            (i === 1) ? descScroll.refresh() : null;
        }
    }
}