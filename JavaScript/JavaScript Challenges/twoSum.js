function twoSum(numArray, sum){
  //Returns every pair of numbers from 'numArray'
  //that adds up to the 'sum'
  //1. Result shoud be an array of arrays
  //2. Any number in the 'numArray' can be used in multiple pairs
  //O(n) not O(n^2)

  var pairs = [];
  var hashtable = [];

  for(var i = 0; i < numArray.length; i++){
    var curNum = numArray[i];
    var counterpart = sum - curNum;
    if (hashtable.indexOf(counterpart) != -1){
      pairs.push([curNum, counterpart]);
    }
    hashtable.push(curNum);
  }

  return pairs;
}

var numArray = [40,11,19,17,-12];
var sum = 28;

twoSum(numArray, sum);