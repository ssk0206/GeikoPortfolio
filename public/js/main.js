/**
 * 確認ダイアログの返り値によりフォーム送信
 */
function submitChk () {
    /* 確認ダイアログ表示 */
    var flag = confirm ( "投稿を削除してもよろしいですか？\n削除したくない場合は[キャンセル]ボタンを押して下さい");
    /* send_flg が TRUEなら送信、FALSEなら送信しない */
    return flag;
}