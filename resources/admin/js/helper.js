import { route } from "ziggy-js";
export const safeRoute = (name, params, absolute, config) => {
    try {
        return route(name, params, absolute, config);
    }
    catch {
        console.warn(`Route ${name} not found`);
        return "#";
    }
};
export const transformObject = (input) => {
    const result = {};
    for (const key in input) {
        if (Object.prototype.hasOwnProperty.call(input, key)) {
            const parts = key.split(".");
            parts.reduce((acc, part, index) => {
                const isLast = index === parts.length - 1;
                const arrayIndex = Number.isInteger(Number(part)) ? Number(part) : NaN;
                if (acc !== null && typeof acc === "object") {
                    const container = acc;
                    if (!Number.isNaN(arrayIndex)) {
                        if (!Array.isArray(container[arrayIndex])) {
                            container[arrayIndex] = [];
                        }
                        if (isLast) {
                            container[arrayIndex] = input[key];
                            return container;
                        }
                        if (container[arrayIndex] === undefined ||
                            typeof container[arrayIndex] !== "object") {
                            container[arrayIndex] = {};
                        }
                        return container[arrayIndex];
                    }
                    if (isLast) {
                        container[part] = input[key];
                        return container;
                    }
                    if (container[part] === undefined || typeof container[part] !== "object") {
                        container[part] = {};
                    }
                    return container[part];
                }
                return acc;
            }, result);
        }
    }
    return result;
};
