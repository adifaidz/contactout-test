import _ from "lodash";

export const formatFormErrors = (key, data, errors) => {
    const pattern = /^([a-z]+.\d*)$/;
    if (!key.match(pattern)) return errors[key];
    return errors[key].replaceAll(key, _.get(data, key));
};

export const createOption = (label) => ({
    label,
    value: label,
});

export const createOptions = (labels) =>
    labels.map((label) => createOption(label));
