const NUMBER_OF_LEAVES = 30;
 
function init()
{
    
    var container = document.getElementById('leafContainer');
   
    for (var i = 0; i < NUMBER_OF_LEAVES; i++) 
    {
        container.appendChild(createALeaf());
    }
}

 function randomInteger(low, high)
{
    return low + Math.floor(Math.random() * (high - low));
}

 
function randomFloat(low, high)
{
    return low + Math.random() * (high - low);
}

 
function pixelValue(value)
{
    return value + 'px';
}
 
function durationValue(value)
{
    return value + 's';
}
 
function createALeaf()
{
    
    if(animationArr ==undefined ||animationArr[animationindex] ==undefined){
        return;
    }
    var leafDiv = document.createElement('div');
    var image = document.createElement('img');
    
   
    image.src = '/index/images/dhimg/'+animationArr[animationindex] + randomInteger(1, 5) + '.png';
    
    leafDiv.style.top = "-100px";

  
    leafDiv.style.left = pixelValue(randomInteger(0, 500));
    
   
    var spinAnimationName = (Math.random() < 0.5) ? 'clockwiseSpin' : 'counterclockwiseSpinAndFlip';
    
    
    leafDiv.style.webkitAnimationName = 'fade, drop';
    image.style.webkitAnimationName = spinAnimationName;
    
   
    var fadeAndDropDuration = durationValue(randomFloat(5, 11));
   
    var spinDuration = durationValue(randomFloat(4, 8));
     
    leafDiv.style.webkitAnimationDuration = fadeAndDropDuration + ', ' + fadeAndDropDuration;

    var leafDelay = durationValue(randomFloat(0, 5));
    leafDiv.style.webkitAnimationDelay = leafDelay + ', ' + leafDelay;

    image.style.webkitAnimationDuration = spinDuration;

 
    leafDiv.appendChild(image);
 
    return leafDiv;
}
 
window.addEventListener('load', init, false);
