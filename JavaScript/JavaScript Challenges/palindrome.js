function isPalindrome(string){
  string = string.toLowerCase();
  var charArr = string.split('');
  var validChar = 'abcdefghijklmnopqrstuvwxyz'.split('');
  
  var lettersArr = [];
  charArr.forEach(char => {
    if (validChar.indexOf(char) > -1) lettersArr.push(char);
  });

  if(lettersArr.join('') === lettersArr.reverse().join('')) return true;
  else return false;
}

isPalindrome("Madam I'm Adam");