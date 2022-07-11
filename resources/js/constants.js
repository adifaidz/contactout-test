export const SelectCustom = {
    styles: {
        input: (provided, state) => ({
            ...provided,
            "input:focus": {
                boxShadow: "none",
            },
        }),
    },
    components: {
        DropdownIndicator: null,
    },
};
