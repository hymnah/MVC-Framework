let eById = function(id) {
    return document.getElementById(id);
}

let eByClass = function(classname) {
    return document.getElementsByClassName(classname);
}

let eQuery = function(query, elem) {
    if (!!elem) {
        return elem.querySelectorAll(query);
    }
    return document.querySelectorAll(query);
}

HTMLElement.prototype.addClass = function(classname) {
    this.classList.add(classname);
    return this;
};

HTMLElement.prototype.remClass = function(classname) {
    this.classList.remove(classname);
    return this;
};

HTMLElement.prototype.hasClass = function(classname) {
    return this.classList.contains(classname);
}

HTMLElement.prototype.setAttr = function(attrName, value) {
    this.attributes[attrName].value = value;
    return this
};

HTMLElement.prototype.getAttr = function(attrName) {
    return this.attributes[attrName].value;
};

function menuLayout() {
    var topBar = eByClass('top-bar')[0];
    var topBarH = window.getComputedStyle(topBar, null).getPropertyValue('height').replace('px', '');
    if (window.scrollY > topBarH) {
        topBar.classList.add('scrolled');
    } else {
        topBar.classList.remove('scrolled');
    }
}

window.onscroll = function () {
    menuLayout();
};



// var menuToggle = eById('menu-toggle');
// var burgerI = eById('burger-i');
//
// menuToggle.onchange = function () {
//     var burgerIClassList = burgerI.classList;
//     var closeIcon = 'fi-sr-menu-burger';
//     var openIcon = 'fi-sr-cross-small';
//     if (this.checked) {
//         burgerIClassList.remove(closeIcon);
//         burgerIClassList.add(openIcon);
//     } else {
//         burgerIClassList.add(closeIcon);
//         burgerIClassList.remove(openIcon);
//     }
// }