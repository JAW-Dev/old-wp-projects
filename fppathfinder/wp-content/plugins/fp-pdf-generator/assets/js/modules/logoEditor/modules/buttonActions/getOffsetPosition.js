// Import Modules
import { isObjectEmpty } from '../../helpers/index';

const getOffsetPosition = element => {
    let xPosition = 0;
    let yPosition = 0;
    while (element && !isNaN(element.offsetLeft) && !isNaN(element.offsetTop)) {
        xPosition += element.offsetLeft - element.scrollLeft;
        yPosition += element.offsetTop - element.scrollTop;
        element = element.offsetParent;
    }
    return { top: yPosition, left: xPosition };
};

export default getOffsetPosition;
