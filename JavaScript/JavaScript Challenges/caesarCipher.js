//My Cipher function before seeing solution
function myCaesarCipher(str, num){
  num = num % 26;
  var charArr = str.split('');
  var alphaArr = 'abcdefghijklmnopqrstuvwxyz'.split('');
  var newArr = [];

  charArr.forEach(char => {
    if(char != ' '){
      newArr.push(cirArray(alphaArr, num + alphaArr.indexOf(char.toLowerCase())));
      if(char === char.toUpperCase()) { 
        newArr.push(newArr.pop().toUpperCase());
      }
    }
    else { 
      newArr.push(' ');
    }
  });

  return newArr.join('').toString();
}

function cirArray(array, num){
  if(num < 0) return array[(array.length)  + num];
  else if(num > array.length - 1) return array[num - (array.length)];
  else return array[num];
}

myCaesarCipher('Zoo Keeper', 2);


//Solution from class
function caesarCipher (str, num){
  num = num % 26;
  var lowerCaseString = str.toLowerCase();
  var alphabet = 'abcdefghijklmnopqrstuvwxyz'.split('');
  var newString = '';

  for (var i = 0; i < lowerCaseString.length; i++){
    var currentLetter = lowerCaseString[i];
    if(currentLetter === ' '){
      newString += currentLetter;
      continue;
    }
    var currentIndex = alphabet.indexOf(currentLetter);
    var newIndex = currentIndex + num;
    if (newIndex > 25) newIndex = newIndex - 26;
    if (newIndex < 0) newIndex = 26 + newIndex;
    if(str[i] === str[i].toUpperCase()){
      newString += alphabet[newIndex].toUpperCase();
    }
    else newString += alphabet[newIndex];
  }
  return newString;
}

caesarCipher('Zoo Keeper', 2);