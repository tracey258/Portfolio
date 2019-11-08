//The one I made before seeing solution
function getMean(array){
  //Add all the numbers in the array together then divide by the length
  var sum = 0;
  for(var i=0; i<array.length; i++){
    sum += array[i]; 
  }

  return sum / array.length;
}

function getMedian(array){
  //Get middle value
  array = arr.sort();
  return array[array.length/2];
}

function getMode(array){
  //Most often repeated number
  var arrObj = {};

  array.forEach(num => {
    if(!arrObj[num]){
      arrObj[num] = 0;
    }
    arrObj[num]++;
  });

  var high = "";
  Object.keys(arrObj).forEach(key => {
    if(!high){
      high = key;
    }
    else if(arrObj[high] < arrObj[key]){
      high = key;
    }
  });

  return high;
}

function meanMedianMode(array){
  //call all 3 functions
  //return obj which has mean, median, and mode in it
  var obj = {};
  obj["Mean: "] = getMean(array).toString();
  obj["Median: "] = getMedian(array).toString();
  obj["Mode: "] = getMode(array).toString();

  return obj;
}

var arr = [1,2,3,4,5,4,6,1]
meanMedianMode(arr);

//Solution
function meanMedianMode(array){
  return {
    mean: getMean(array),
    median: getMedian(array),
    mode: getMode(array)
  };
}

function getMean(array){
  var sum = 0;

  array.forEach(num => {
    sum += num;
  });

  return sum / array.length;
}

function getMedian(array){
  array.sort(function(a,b) {return a - b});
  var median;

  if (array.length % 2 !== 0){
    median = array[Math.floor(array.length/2)];
  }
  else {
    var mid1 = array[(array.length/2) - 1];
    var mid2 = array[array.length/2];
    median = (mid1 + mid2) /2;
  }

  return median;
}

function getMode(array){
  var modeObj = {};

  array.forEach(num => {
    if(!modeObj[num]) modeObj[num] = 0;
    modeObj[num]++;
  });

  var maxFreq = 0;
  var modes = [];
  for (var num in modeObj){
    if (modeObj[num] > maxFreq){
      modes = [num];
      maxFreq = modeObj[num];
    }
    else if(modeObj[num] === maxFreq) modes.push(num);
  }

  if(modes.length === Object.keys(modeObj).length) modes = [];

  return modes;
}

meanMedianMode([1,2,3,4,5,4,6,1]);