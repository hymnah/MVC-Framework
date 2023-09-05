var showPass = eById('show-pass');
var pass = eById('password');

function togglePassword(e) {
    var passFieldType = pass.getAttr('type');
    var passIcon = e.children[0];

    if (passFieldType === 'password') {
        pass.setAttr('type', 'text');
        passIcon
            .addClass('fi-rr-unlock')
            .remClass('fi-rr-lock')
    } else {
        pass.setAttr('type', 'password');
        passIcon
            .addClass('fi-rr-lock')
            .remClass('fi-rr-unlock')
    }
}

showPass.onclick = function () {
    togglePassword(this);
}