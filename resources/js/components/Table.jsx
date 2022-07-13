import React, { useState } from "react";

export default function Tabs({ headers, data }) {
    return data.length ? (
        <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    {headers.map((header) => {
                        return (
                            <th scope="col" className="py-3 px-6">
                                {header.label}
                            </th>
                        );
                    })}
                </tr>
            </thead>
            <tbody>
                {data.map((row) => {
                    return (
                        <tr className="bg-white border-b">
                            {headers.map((header) => {
                                return (
                                    <td className="py-4 px-6">
                                        {row[header.property]}
                                    </td>
                                );
                            })}
                        </tr>
                    );
                })}
            </tbody>
        </table>
    ) : (
        <div className="w-full text-gray-500 text-center mt-4">
            No record was found
        </div>
    );
}
