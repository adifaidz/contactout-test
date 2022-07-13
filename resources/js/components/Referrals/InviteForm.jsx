import React, { useState } from "react";
import Select from "react-select/creatable";
import Button from "@/Components/Button";
import ValidationErrors from "@/Components/ValidationErrors";
import { useForm } from "@inertiajs/inertia-react";
import { z } from "zod";
import { createOptions } from "@/helpers";
import { SelectCustom } from "@/constants";

const InviteFormValidator = z.array(z.string().email());

export default function InviteForm() {
    const [inputValue, setInputValue] = useState("");
    const { data, setData, post, processing, errors, setError, clearErrors } =
        useForm({
            emails: [],
        });

    const clearErrorMessage = () => {
        if (errors) clearErrors();
    };

    const onHandleChange = (emails) => {
        clearErrorMessage();
        setData(
            "emails",
            emails.map((email) => email.value)
        );
    };

    const onHandleInputChange = (inputValue) => {
        clearErrorMessage();
        setInputValue(inputValue);
    };

    const handleOnSubmit = (e) => {
        e.preventDefault();
        post(route("referrals.invite"), {
            onSuccess: () => {
                setData({ emails: [] });
            },
        });
    };

    const onHandleKeyDown = (event) => {
        clearErrorMessage();
        if (!inputValue) return;

        switch (event.key) {
            case "Enter":
            case "Tab":
                event.preventDefault();
                try {
                    const duplicate = data.emails.find(
                        (email) => email === inputValue
                    );

                    if (duplicate) {
                        setInputValue("");
                        return;
                    }

                    InviteFormValidator.parse([inputValue]);
                    setData("emails", [...data.emails, inputValue]);
                    setInputValue("");
                } catch (error) {
                    setError(
                        "emails",
                        "The input must be a valid email address."
                    );
                    setInputValue("");
                }
        }
    };

    const disableEnter = (e) => {
        if (e.keyCode == "13") {
            e.preventDefault();
            return;
        }
    };

    const emailOptions = createOptions(data.emails);

    return (
        <>
            <div className="mt-4">
                <ValidationErrors data={data} errors={errors} />
            </div>
            <form
                onSubmit={handleOnSubmit}
                onKeyDown={disableEnter}
                className="mt-4"
            >
                <div className="flex justify-start w-full">
                    <Select
                        components={SelectCustom.components}
                        styles={SelectCustom.styles}
                        isClearable
                        isMulti
                        menuIsOpen={false}
                        onChange={onHandleChange}
                        onInputChange={onHandleInputChange}
                        onKeyDown={onHandleKeyDown}
                        name="emails"
                        className="email-select w-6/12"
                        placeholder="Emails to invite"
                        inputValue={inputValue}
                        value={emailOptions}
                    />
                    <div className="flex items-center justify-center w-2/12 pl-2">
                        <Button
                            className="w-full justify-center text-center"
                            processing={processing}
                        >
                            Invite
                        </Button>
                    </div>
                </div>
            </form>
        </>
    );
}
