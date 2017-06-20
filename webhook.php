function check(){
  if (isset($_POST['leads']) {
    return true; 
  } else {
    sleep(5);
    check();
  }
}

check();

