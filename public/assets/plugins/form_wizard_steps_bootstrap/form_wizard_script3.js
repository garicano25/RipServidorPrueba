//DOM elements
const DOMstrings3 = {
    stepsBtnClass: 'multisteps-form__progress-btn-3',
    stepsBtns: document.querySelectorAll(`.multisteps-form__progress-btn-3`),
    stepsBar: document.querySelector('.multisteps-form__progress-3'),
    stepsForm: document.querySelector('.multisteps-form__form-3'),
    // stepsFormTextareas: document.querySelectorAll('.multisteps-form__textarea'),
    stepFormPanelClass: 'multisteps-form__panel-3',
    stepFormPanels: document.querySelectorAll('.multisteps-form__panel-3'),
    stepPrevBtnClass: 'js-btn-prev',
    stepNextBtnClass: 'js-btn-next'
};
//remove class from a set of items
const removeClasses3 = (elemSet, className) => {
    elemSet.forEach(elem => {
        elem.classList.remove(className);
    });
};
//return exect parent node of the element
const findParent3 = (elem, parentClass) => {
    let currentNode = elem;
    while (!currentNode.classList.contains(parentClass)) {
        currentNode = currentNode.parentNode;
    }
    return currentNode;
};
//get active button step number
const getActiveStep3 = elem => {
    return Array.from(DOMstrings3.stepsBtns).indexOf(elem);
};
//set all steps before clicked (and clicked too) to active
const setActiveStep3 = activeStepNum => {
    //remove active state from all the state
    removeClasses3(DOMstrings3.stepsBtns, 'js-active');
    //set picked items to active
    DOMstrings3.stepsBtns.forEach((elem, index) => {
        if (index <= activeStepNum) {
            elem.classList.add('js-active');
        }
    });
};
//get active panel
const getActivePanel3 = () => {
    let activePanel;
    DOMstrings3.stepFormPanels.forEach(elem => {
        if (elem.classList.contains('js-active')) {
            activePanel = elem;
        }
    });
    return activePanel;
};
//open active panel (and close unactive panels)
const setActivePanel3 = activePanelNum => {
    //remove active class from all the panels
    removeClasses3(DOMstrings3.stepFormPanels, 'js-active');
    //show active panel
    DOMstrings3.stepFormPanels.forEach((elem, index) => {
        if (index === activePanelNum) {
            elem.classList.add('js-active');
            setFormHeight3(elem);
        }
    });
};
//set form height equal to current panel height
const formHeight3 = activePanel => {
    const activePanelHeight = activePanel.offsetHeight;
    DOMstrings3.stepsForm.style.height = `${activePanelHeight}px`;
};
const setFormHeight3 = () => {
    const activePanel = getActivePanel3();
    formHeight3(activePanel);
};
//STEPS BAR CLICK FUNCTION
DOMstrings3.stepsBar.addEventListener('click', e => {
    
        //check if click target is a step button
        const eventTarget = e.target;
        if (!eventTarget.classList.contains(`${DOMstrings3.stepsBtnClass}`)) {
            return;
        }
        //get active button step number
        const activeStep = getActiveStep3(eventTarget);

        //set all steps before clicked (and clicked too) to active
        setActiveStep3(activeStep);
        //open active panel
        setActivePanel3(activeStep);
});
//PREV/NEXT BTNS CLICK
DOMstrings3.stepsForm.addEventListener('click', e => {
    const eventTarget = e.target;
    //check if we clicked on `PREV` or NEXT` buttons
    if (!(eventTarget.classList.contains(`${DOMstrings3.stepPrevBtnClass}`) || eventTarget.classList.contains(`${DOMstrings3.stepNextBtnClass}`))) {
        return;
    }
    //find active panel
    const activePanel = findParent3(eventTarget, `${DOMstrings3.stepFormPanelClass}`);
    let activePanelNum = Array.from(DOMstrings3.stepFormPanels).indexOf(activePanel);
    //set active step and active panel onclick
    if (eventTarget.classList.contains(`${DOMstrings3.stepPrevBtnClass}`)) {
        activePanelNum--;
    } else {
        activePanelNum++;
    }

    //set all steps before clicked (and clicked too) to active
    setActiveStep3(activePanelNum);
    //open active panel
    setActivePanel3(activePanelNum);
});
//SETTING PROPER FORM HEIGHT ONLOAD
window.addEventListener('load', setFormHeight3, false);
//SETTING PROPER FORM HEIGHT ONRESIZE
window.addEventListener('resize', setFormHeight3, false);
//changing animation via animation select !!!YOU DON'T NEED THIS CODE (if you want to change animation type, just change form panels data-attr)
const setAnimationType3 = newType => {
    DOMstrings3.stepFormPanels.forEach(elem => {
        elem.dataset.animation = newType;
    });
};
//selector onchange - changing animation
const animationSelect3 = document.querySelector('.pick-animation__select');
// comentado por GJC
// animationSelect3.addEventListener('change', () => {
//     const newAnimationType = animationSelect3.value;
//     setAnimationType3(newAnimationType);
// });