function reverseWords(str){
  //reverse every word in the string
  //return the new string
  //cannot use array.reverse()
  var wordArr = str.split(' ');
  var newArr = [];

  wordArr.forEach(word => {
    var reversedWord = '';
    for(var i=word.length - 1; i >= 0; i--){
      reversedWord += word[i];
    }
    newArr.push(reversedWord);
  });
  return newArr.join(' ');
}

reverseWords("Coding JavaScript");