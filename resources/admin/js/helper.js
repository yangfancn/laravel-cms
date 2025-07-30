import { route } from "ziggy-js";
export const safeRoute = (name, params, absolute, config) => {
    try {
        return route(name, params, absolute, config);
    }
    catch (error) {
        console.warn(`Route ${name} not found`);
        return "#";
    }
};
export const transformObject = (input) => {
    const result = {};
    for (const key in input) {
        if (input.hasOwnProperty(key)) {
            const parts = key.split(".");
            parts.reduce((acc, part, index) => {
                const isLast = index === parts.length - 1;
                const arrayIndex = parseInt(part, 10);
                if (!isNaN(arrayIndex)) {
                    if (!Array.isArray(acc[arrayIndex])) {
                        acc[arrayIndex] = [];
                    }
                    if (isLast) {
                        acc[arrayIndex] = input[key];
                    }
                    else {
                        if (!acc[arrayIndex]) {
                            acc[arrayIndex] = {};
                        }
                        return acc[arrayIndex];
                    }
                }
                else {
                    if (isLast) {
                        acc[part] = input[key];
                    }
                    else {
                        if (!acc[part]) {
                            acc[part] = {};
                        }
                        return acc[part];
                    }
                }
            }, result);
        }
    }
    return result;
};
