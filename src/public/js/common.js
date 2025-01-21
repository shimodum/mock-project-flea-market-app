// 目のマークをクリックした時に、パスワード入力欄の表示・非常時を切り替える関数
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}
