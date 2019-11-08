  //reverse arr
  //return reversed arr
  //Be sure to manipulate the array passed in
  //Do NOT push elements into a new array and return that array
  //Do not use array.reverse() method

  for(var i=0; i < (arr.length /2); i++){
    var temp = arr[i];
    arr[i] = arr[(arr.length -1) - (i)];
    arr[(arr.length -1) - (i)] = temp;
  }

  return arr;
}

reverseArrayInPlace([1,2,3,4,5,6,7]);