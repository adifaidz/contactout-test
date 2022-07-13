import React, { useState } from "react";

const classes = {
    activeTabClass:
        "inline-block bg-gray-100 text-blue-600 rounded-t-lg py-4 px-4 text-sm font-medium text-center active",
    normalTabClass:
        "inline-block text-gray-500 hover:text-gray-600 hover:bg-gray-50 rounded-t-lg py-4 px-4 text-sm font-medium text-center",
};

export default function Tabs({ tabs, onTabChange, currentTab }) {
    return (
        <ul class="flex flex-wrap border-b border-gray-200 mt-4">
            {tabs.map((tab, index) => {
                return (
                    <li class="mr-2" onClick={() => onTabChange(index)}>
                        <a
                            href="#"
                            class={
                                index === currentTab
                                    ? classes.activeTabClass
                                    : classes.normalTabClass
                            }
                        >
                            {tab}
                        </a>
                    </li>
                );
            })}
        </ul>
    );
}
