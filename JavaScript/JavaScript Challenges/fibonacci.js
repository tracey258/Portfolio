function fibonacci(position){
  //1,1,2,3,5,8,13,21,34...
  //fibonacci(4) returns 3
  //fibonacci(9) returns 34

  if(position < 3){
    return 1;
  }
  else{
    return fibonacci(position - 1) + fibonacci(position - 2);
  }
}

fibonacci(3);