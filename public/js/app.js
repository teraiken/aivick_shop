function deleteAlert(e){
  if(!window.confirm('本当に削除しますか？')){
     return false;
  }
  document.deleteform.submit();
};