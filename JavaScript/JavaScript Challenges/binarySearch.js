function binarySearch(numArray, key){
  var midIdx = Math.floor(numArray.length / 2);
  var midElm = numArray[midIdx];

  if (midElm === key){
    return true;
  }
  else if(midElm < key && numArray.length > 1){
    return binarySearch(numArray.splice(midIdx, numArray.length), key);
  }
  else if(midElm > key && numArray.length > 1){
    return binarySearch(numArray.splice(0, midIdx), key);
  }
  else{
    return false;
  }
}

binarySearch([5,7,12,16,36,39,42,56,71], 56);