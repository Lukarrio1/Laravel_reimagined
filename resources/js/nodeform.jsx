function createQueryString(params) {
    const queryString = Object.keys(params)
        .map(
            (key) =>
                encodeURIComponent(key) +
                "=" +
                encodeURIComponent(params[key] ? params[key] : null)
        )
        .join("&");
    return queryString;
}
function getPreviousElement(obj, currentKey) {
    // Get all the keys of the object
    const keys = Object.keys(obj);

    // Find the index of the current key
    const currentIndex = keys.indexOf(currentKey);

    // Check if there is a previous key
    if (currentIndex > 0) {
        const previousKey = keys[currentIndex - 1];
        return { obj: obj[previousKey], key: previousKey };
    } else {
        return null; // Return null if there is no previous element
    }
}

function getUniqueElements(array) {
    return array.filter((value, index, self) => self.indexOf(value) === index);
}
function TableToJoin({
    table_columns,
    columns,
    table,
    node,
    query_conditions,
    setSelectedTables,
    MainTable,
}) {
    const previousElement = getPreviousElement(table_columns, table);
    console.log(previousElement, "key here");
    return (
        <>
            {previousElement?.key != null && (
                <div class="mb-3">
                    <label for="node_join_column" class="form-label">
                        Node {previousElement?.key ?? MainTable} Column To Join
                        By
                    </label>
                    <select
                        id={`node_previous_${table}_join_column`}
                        class="form-select"
                        name={`node_previous_${table}_join_column`}
                        required
                    >
                        <option value="">Select column</option>
                        {previousElement?.obj &&
                            previousElement?.obj?.map((column) => {
                                return (
                                    <option
                                        selected={
                                            node?.properties?.value[
                                                `node_previous_${table}_join_column`
                                            ] == column
                                        }
                                        value={column}
                                    >
                                        {column}
                                    </option>
                                );
                            })}
                    </select>
                </div>
            )}
            <div class="mb-3">
                <label for="node_join_by_column" class="form-label">
                    Join {table} By Condition{" "}
                    <button
                        class="btn btn-danger btn-sm h4"
                        title="Remove join entry"
                        onClick={(e) => {
                            e.preventDefault();
                            setSelectedTables((pre) => [
                                ...pre?.filter((c) => c != table),
                            ]);
                        }}
                    >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </label>
                <select
                    id={`node_${table}_join_by_condition`}
                    class="form-select"
                    name={`node_${table}_join_by_condition`}
                    required
                >
                    <option value="">Select join by condition</option>
                    {query_conditions &&
                        query_conditions.map((condition) => {
                            return (
                                <option
                                    selected={
                                        node?.properties?.value[
                                            `node_${table}_join_by_condition`
                                        ] == condition
                                    }
                                    value={condition}
                                >
                                    {condition}
                                </option>
                            );
                        })}
                </select>
            </div>
            <div class="mb-3">
                <label for="node_join_by_column" class="form-label">
                    Join {table} to {previousElement?.key ?? MainTable} By
                </label>
                <select
                    id={`node_${table}_join_by_column`}
                    class="form-select"
                    name={`node_${table}_join_by_column`}
                    required
                >
                    <option value="">Select join by column</option>
                    {columns &&
                        columns.map((column) => {
                            return (
                                <option
                                    selected={
                                        node?.properties?.value[
                                            `node_${table}_join_by_column`
                                        ] == column
                                    }
                                    value={column}
                                >
                                    {column}
                                </option>
                            );
                        })}
                </select>
            </div>
            <div class="mb-3">
                <label for="node_table" class="form-label">
                    Node {table} Columns To Display
                </label>
                <select
                    id="node_table_columns"
                    class="form-select"
                    name={`node_${table}_join_columns[]`}
                    multiple={true}
                    required
                >
                    <option value="">Select A Table Columns</option>
                    {columns &&
                        columns?.map((column) => {
                            return (
                                <option
                                    selected={node?.properties?.value[
                                        `node_${table}_join_columns`
                                    ]?.includes(column)}
                                    value={column}
                                >
                                    {column}
                                </option>
                            );
                        })}
                </select>
            </div>
        </>
    );
}

function JoinTablesForm({
    mainColumns,
    node,
    mainTables,
    database,
    MainTable,
}) {
    const [selectedTables, setSelectedTables] = React.useState([]);
    const [tablesToJoin, setTablesToJoin] = React.useState({});
    const [query_conditions, setQueryConditions] = React.useState([]);

    const getTableData = async () => {
        const { data } = await axios.get(
            "/node/databus/tableData?" +
                createQueryString({ tables: selectedTables ?? "", database })
        );
        setTablesToJoin(data.tables_with_columns);
        setQueryConditions(data.query_conditions);
        console.log(data, "table data here");
    };

    React.useEffect(() => {
        getTableData();
    }, [selectedTables]);

    React.useEffect(() => {
        if (!node) return;
        if (!node?.properties?.value?.node_join_tables) return;
        setSelectedTables(
            JSON.parse(node?.properties?.value?.node_join_tables)
        );
    }, [node]);

    return (
        <>
            <div class="mb-3">
                <div className="card">
                    <div className="card-body text-center h4">
                        Use with caution, this is still in beta . (leave fields
                        blank if you don't intend to use nested joins).
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="node_join_column" class="form-label">
                    Node Column To Join By
                </label>
                <select
                    id="node_join_column"
                    class="form-select"
                    name="node_join_column"
                    onChange={(e) => {}}
                >
                    <option value="">Select column</option>
                    {mainColumns &&
                        mainColumns.map((column) => {
                            return (
                                <option
                                    selected={
                                        node?.properties?.value
                                            ?.node_join_column == column
                                    }
                                    value={column}
                                >
                                    {column}
                                </option>
                            );
                        })}
                </select>
            </div>
            <div class="mb-3">
                <label for="node_table" class="form-label">
                    Node Tables To Join To {JSON.stringify(selectedTables)}
                    {/* {[
                        "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                    ]?.includes(route_function_value?.split("_")[0])
                        ? JSON.stringify(columns_to_save)
                        : ""} */}
                </label>
                <select
                    id="node_join_tables"
                    class="form-select"
                    // name="node_join_tables"
                    onChange={(e) => {
                        setSelectedTables(
                            [
                                ...selectedTables?.filter(
                                    (c, idx) => c != e.target.value
                                ),
                                e.target.value,
                            ].filter((c, idx) => idx < 2)
                        );
                    }}
                >
                    <option value="">Select table</option>
                    {mainTables &&
                        mainTables.map((table) => {
                            return <option value={table}>{table}</option>;
                        })}
                </select>
            </div>
            <input
                type="hidden"
                value={JSON.stringify(selectedTables)}
                name={"node_join_tables"}
            />
            {Object.keys(tablesToJoin)?.map(function (key) {
                return (
                    key.length > 0 && (
                        <TableToJoin
                            table_columns={tablesToJoin}
                            table={key}
                            columns={tablesToJoin[key]}
                            node={node}
                            query_conditions={query_conditions}
                            setSelectedTables={setSelectedTables}
                            MainTable={MainTable}
                        ></TableToJoin>
                    )
                );
            })}
        </>
    );
}

function App() {
    const possibleLabel = {
        "App\\Http\\Controllers\\Api\\DataBusController::oneRecord":
            " (use route parameters based on columns from the database to filter the data eg. test/{column}/{column1})",
        "App\\Http\\Controllers\\Api\\DataBusController::manyRecords":
            " (use route parameters based on columns from the database to filter the data eg. test/{column}/{column1})",
        "App\\Http\\Controllers\\Api\\DataBusController::saveRecord":
            " (route parameters are not required for this route eg. save/route)",
        "App\\Http\\Controllers\\Api\\DataBusController::checkRecord":
            " (use route parameters based on columns from the database to filter the data eg. test/{column}/{column1})",
        "App\\Http\\Controllers\\Api\\DataBusController::updateRecord":
            " (use route parameters based on columns from the database to find the record(s) that requires an update eg. test/{column}/{column1}))",
        "App\\Http\\Controllers\\Api\\DataBusController::deleteRecord":
            " (use route parameters based on columns from the database to filter the record(s) that you want to delete eg. test/{column}/{column1}))",
        "App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint":
            " (use route parameters based on columns from the database to filter the data eg. test/{column}/{column1})",
    };

    const node_type = document.querySelector("#node_type");
    const route_function = document.querySelector("#route_function");
    const node_id = document.querySelector("#node_id");
    const node_route_label = document.querySelector("#node_route_label");
    const [launch, setLaunch] = React.useState(false);
    const [databases, setDatabases] = React.useState([]);
    const [tables, setTables] = React.useState([]);
    const [selected_database, setSelectedDatabases] = React.useState(null);
    const [selected_table, setSelectedTable] = React.useState(null);
    const [selected_columns, setSelectedTableColumns] = React.useState(null);
    const [columns, setTableColumns] = React.useState(null);
    const [node, setNode] = React.useState(null);
    const [node_many_data, setNodeManyData] = React.useState(null);
    const [display_aid, setNodeDisplayAid] = React.useState(null);
    const [table_items, setTableItems] = React.useState([]);
    const [table_items_display_aids, setTableItemsDisplayAids] = React.useState(
        []
    );
    const [node_item, setNodeItem] = React.useState(null);
    const [route_function_value, setRouteFunctionValue] = React.useState(null);
    const [orderByTypes, setOrderByTypes] = React.useState(null);
    const [nodeType, setNodeType] = React.useState(null);
    const [selected_order_by_type, setSelectedOrderByTypes] =
        React.useState(null);
    const [data_limit, setDataLimit] = React.useState(null);
    const [validation_rules, setValidationRules] = React.useState([]);
    const [columns_to_save, setColumnsToSave] = React.useState([]);
    const [node_endpoint_to_consume, setNodeEndpointToConsume] =
        React.useState("");

    const getData = async () => {
        const { data } = await axios.get(
            "/node/databus?" +
                createQueryString({
                    database: selected_database,
                    table: selected_table,
                    node_id: node_id?.value,
                    node_table_columns: selected_columns,
                    node_has_many: node_many_data,
                    node_url_to_consume: node_endpoint_to_consume,
                    display_aid: display_aid,
                })
        );

        setDatabases(data?.databases);
        setTables(data?.tables);
        setTableColumns(data?.columns);
        setNode(data?.node);
        setOrderByTypes(data?.orderByTypes);
        setTableItems(data?.table_items);
        setValidationRules(data?.validation_rules);
        setTableItemsDisplayAids(data?.display_aid_columns);
    };

    React.useEffect(() => {
        node_type.addEventListener("change", (e) => {
            setNodeType(+e.target.value);
            setLaunch(+e.target.value === 1);
        });
        if (route_function)
            route_function.addEventListener("change", (e) => {
                setRouteFunctionValue(e.target.value);
                setLaunch(
                    [
                        "App\\Http\\Controllers\\Api\\DataBusController::oneRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::manyRecords",
                        "App\\Http\\Controllers\\Api\\DataBusController::checkRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::deleteRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint",
                    ].includes(e.target.value.split("_")[0]) == true
                );
            });
    }, [launch]);

    React.useEffect(() => {
        setNodeType(+node_type?.value);
    }, [node_type]);

    React.useEffect(() => {
        getData();
        // setColumnsToSave(selected_columns);
    }, [
        selected_database,
        selected_table,
        selected_columns,
        nodeType,
        route_function,
        node_endpoint_to_consume,
        display_aid,
    ]);

    React.useEffect(() => {
        if (!node) return;
        setSelectedDatabases(node?.properties?.value?.node_database ?? null);
        setSelectedTable(node?.properties?.value?.node_table ?? null);
        setRouteFunctionValue(route_function?.value);
        setDataLimit(node?.properties?.value?.node_data_limit ?? null);
        setNodeDisplayAid(
            node?.properties?.value?.node_item_display_aid ?? display_aid
        );
        setNodeEndpointToConsume((pre) =>
            node_endpoint_to_consume?.length > 0
                ? node_endpoint_to_consume
                : node?.properties?.value?.node_endpoint_to_consume
        );
        // setNodeDisplayAid(
        //     node?.properties?.value?.node_item_display_aid ?? null
        // );
        // setSelectedTableColumns(node?.properties?.value?.node_table_columns);
        setColumnsToSave((pre) =>
            getUniqueElements([
                ...(pre ?? []),
                ...(node?.properties?.value?.node_table_columns ?? []),
            ])
        );
        setLaunch(false);
        setLaunch(true);
    }, [node]);

    React.useEffect(() => {
        if (!node_route_label) return;
        node_route_label.innerHTML =
            possibleLabel[route_function_value?.split("_")[0]];
    }, [route_function_value]);

    React.useEffect(() => {
        console.log("this is the selected columns", columns_to_save);
    }, [columns_to_save]);

    // React.useEffect(() => {
    //     if (!node_endpoint_to_consume || !display_aid) return;
    //     getData();
    // }, [node_endpoint_to_consume,]);

    return (
        launch &&
        nodeType == 1 && (
            <div>
                {"App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint" !=
                    route_function_value?.split("_")[0] && (
                    <>
                        <div class="mb-3">
                            <label for="node_database" class="form-label">
                                Node Database
                            </label>
                            <select
                                id="node_database"
                                class="form-select"
                                name="node_database"
                                onChange={(e) =>
                                    setSelectedDatabases(e.target.value)
                                }
                            >
                                <option>Select A Database</option>
                                {databases &&
                                    databases.map((database) => {
                                        return (
                                            <option
                                                selected={
                                                    node?.properties?.value
                                                        ?.node_database ==
                                                    database
                                                }
                                                value={database}
                                            >
                                                {database}
                                            </option>
                                        );
                                    })}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="node_table" class="form-label">
                                Node Table
                            </label>
                            <select
                                id="node_table"
                                class="form-select"
                                name="node_table"
                                onChange={(e) =>
                                    setSelectedTable(e.target.value)
                                }
                            >
                                <option value="">Select A Table</option>
                                {tables &&
                                    tables.map((table) => {
                                        return (
                                            <option
                                                selected={
                                                    node?.properties?.value
                                                        ?.node_table == table
                                                }
                                                value={table}
                                            >
                                                {table}
                                            </option>
                                        );
                                    })}
                            </select>
                        </div>
                    </>
                )}
                {"App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint" ==
                    route_function_value?.split("_")[0] && (
                    <>
                        <div class="mb-3">
                            <label
                                for="node_endpoint_to_consume"
                                class="form-label"
                            >
                                Node Endpoint To Consume (Current:{" "}
                                <span className="text-danger">
                                    {" "}
                                    {
                                        node?.properties?.value
                                            ?.node_endpoint_to_consume
                                    }
                                </span>
                                )
                            </label>
                            <input
                                type="url"
                                class="form-control"
                                id="node_endpoint_to_consume"
                                aria-describedby="node_name"
                                name="node_endpoint_to_consume"
                                onKeyUp={(e) =>
                                    setTimeout(
                                        () =>
                                            setNodeEndpointToConsume(
                                                e.target.value
                                            ),
                                        2000
                                    )
                                }
                            />
                        </div>
                        {node_endpoint_to_consume && (
                            <input
                                type="hidden"
                                name="node_endpoint_to_consume"
                                value={node_endpoint_to_consume}
                            ></input>
                        )}
                    </>
                )}
                {[
                    "App\\Http\\Controllers\\Api\\DataBusController::oneRecord",
                    "App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint",
                ].includes(route_function_value?.split("_")[0]) && (
                    <>
                        <div class="mb-3">
                            <label
                                for="node_item_display_aid"
                                class="form-label"
                            >
                                Node Item Display Aid
                            </label>
                            <select
                                id="node_item_display_aid"
                                class="form-select"
                                name="node_item_display_aid"
                                onChange={(e) =>
                                    setNodeDisplayAid(e.target.value)
                                }
                            >
                                <option value="">Select display aid</option>
                                {table_items_display_aids &&
                                    table_items_display_aids.map((column) => {
                                        return (
                                            <option
                                                selected={
                                                    node?.properties?.value
                                                        ?.node_item_display_aid ==
                                                    column
                                                }
                                                value={column}
                                            >
                                                {column}
                                            </option>
                                        );
                                    })}
                            </select>
                        </div>
                        {"App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint" !=
                            route_function_value?.split("_")[0] && (
                            <div class="mb-3">
                                <label for="node_table" class="form-label">
                                    Node Item
                                </label>
                                <select
                                    id="node_item"
                                    class="form-select"
                                    name="node_item"
                                    onChange={(e) =>
                                        setNodeItem(e.target.value)
                                    }
                                >
                                    <option value="">Select node item</option>
                                    {display_aid &&
                                        table_items &&
                                        table_items.map((item) => {
                                            return (
                                                <option
                                                    selected={
                                                        node?.properties?.value
                                                            ?.node_item ==
                                                        item?.id
                                                    }
                                                    value={item.id}
                                                >
                                                    {item[display_aid]}
                                                </option>
                                            );
                                        })}
                                </select>
                            </div>
                        )}
                    </>
                )}
                {"App\\Http\\Controllers\\Api\\DataBusController::deleteRecord" !=
                    route_function_value?.split("_")[0] && (
                    <div class="mb-3">
                        <label for="node_table" class="form-label">
                            Node Table Columns{" "}
                            {[
                                "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                                "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                            ]?.includes(route_function_value?.split("_")[0])
                                ? JSON.stringify(columns_to_save)
                                : ""}
                        </label>
                        <select
                            id="node_table_columns"
                            class="form-select"
                            name="node_table_columns[]"
                            onChange={(e) => {
                                setSelectedTableColumns(e.target.value);
                                if (
                                    [
                                        "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                                        "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                                    ]?.includes(
                                        route_function_value?.split("_")[0]
                                    )
                                )
                                    setColumnsToSave([
                                        ...columns_to_save,
                                        e.target.value,
                                    ]);
                            }}
                            multiple={
                                [
                                    "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                                    "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                                ]?.includes(route_function_value?.split("_")[0])
                                    ? false
                                    : true
                            }
                            disabled={[
                                "App\\Http\\Controllers\\Api\\DataBusController::deleteRecord",
                                "App\\Http\\Controllers\\Api\\DataBusController::checkRecord",
                            ]?.includes(route_function_value?.split("_")[0])}
                        >
                            <option value="">Select A Table Columns</option>
                            {columns &&
                                columns
                                    ?.filter((c) =>
                                        [
                                            "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                                            "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                                        ]?.includes(
                                            route_function_value?.split("_")[0]
                                        )
                                            ? !columns_to_save.includes(c)
                                            : true
                                    )
                                    ?.map((column) => {
                                        return (
                                            <option
                                                selected={node?.properties?.value?.node_table_columns?.includes(
                                                    column
                                                )}
                                                // onClick={() =>
                                                //     setColumnsToSave((pre) => [
                                                //         ...pre?.filter(
                                                //             (c) => c != column
                                                //         ),
                                                //         column,
                                                //     ])
                                                // }
                                                value={column}
                                            >
                                                {column}
                                            </option>
                                        );
                                    })}
                        </select>
                    </div>
                )}
                {[
                    "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                    "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                ]?.includes(route_function_value?.split("_")[0]) && (
                    <>
                        <input
                            type="hidden"
                            value={columns_to_save?.length}
                            name="node_endpoint_length"
                        ></input>
                        <input
                            type="hidden"
                            value={JSON.stringify(
                                columns_to_save.length > 0
                                    ? columns_to_save
                                    : selected_columns
                            )}
                            name="node_endpoint_columns"
                        ></input>
                    </>
                )}
                {[
                    "App\\Http\\Controllers\\Api\\DataBusController::saveRecord",
                    "App\\Http\\Controllers\\Api\\DataBusController::updateRecord",
                ]?.includes(route_function_value?.split("_")[0]) &&
                    columns_to_save?.length > 0 &&
                    columns_to_save.map(function (column, idx) {
                        return (
                            <div class="mb-3">
                                <label
                                    for={"node_endpoint_field_" + column}
                                    class="form-label"
                                >
                                    Node Endpoint Field {column}
                                    {"  "}
                                    <button
                                        class="btn btn-danger btn-sm h4"
                                        title="Remove Endpoint Field"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            setColumnsToSave([
                                                ...columns_to_save?.filter(
                                                    (c) => c != column
                                                ),
                                            ]);
                                        }}
                                    >
                                        <i
                                            class="fa fa-trash"
                                            aria-hidden="true"
                                        ></i>
                                    </button>
                                </label>
                                <select
                                    id={"node_endpoint_field_" + column}
                                    class="form-select"
                                    name={"node_endpoint_field_" + idx + "[]"}
                                    // onChange={(e) =>
                                    //     setSelectedTableColumns(e.target.value)
                                    // }
                                    multiple
                                    // disabled={[
                                    //     "App\\Http\\Controllers\\Api\\DataBusController::deleteRecord",
                                    //     "App\\Http\\Controllers\\Api\\DataBusController::checkRecord",
                                    // ]?.includes(
                                    //     route_function_value?.split("_")[0]
                                    // )}
                                >
                                    <option value="">
                                        Select Validation Rules
                                    </option>
                                    {validation_rules &&
                                        validation_rules.map((rule) => {
                                            // node_endpoint_field
                                            return (
                                                <option
                                                    selected={node?.properties?.value[
                                                        `node_endpoint_field_${column}`
                                                    ]?.includes(rule)}
                                                    value={rule}
                                                >
                                                    {rule}
                                                </option>
                                            );
                                        })}
                                </select>
                            </div>
                        );
                    })}
                {[
                    "App\\Http\\Controllers\\Api\\DataBusController::manyRecords",
                    "App\\Http\\Controllers\\Api\\DataBusController::consumeGetEndPoint",
                ]?.includes(route_function_value?.split("_")[0]) && (
                    <>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Node Data Limit
                            </label>
                            <input
                                type="number"
                                class="form-control"
                                id="node_data_limit"
                                aria-describedby="node_name"
                                name="node_data_limit"
                                placeholder={
                                    node?.properties?.value?.node_data_limit > 0
                                        ? node?.properties?.value
                                              ?.node_data_limit
                                        : 0
                                }
                                value={data_limit}
                                onChange={(e) => setDataLimit(e.target.value)}
                            />
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Node Data Order By Field
                            </label>
                            <select
                                id="node_order_by_field"
                                class="form-select"
                                name="node_order_by_field"
                                onChange={(e) =>
                                    setNodeDisplayAid(e.target.value)
                                }
                            >
                                <option>Select field to order by</option>
                                {columns &&
                                    columns.map((column) => {
                                        return (
                                            <option
                                                selected={
                                                    node?.properties?.value
                                                        ?.node_order_by_field ==
                                                    column
                                                }
                                                value={column}
                                            >
                                                {column}
                                            </option>
                                        );
                                    })}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Node Data Order By
                            </label>
                            <select
                                id="node_order_by_type"
                                class="form-select"
                                name="node_order_by_type"
                                onChange={(e) =>
                                    setSelectedOrderByTypes(e.target.value)
                                }
                            >
                                <option>Select field to order by</option>
                                {orderByTypes &&
                                    orderByTypes.map((type) => {
                                        return (
                                            <option
                                                selected={
                                                    node?.properties?.value
                                                        ?.node_order_by_type ==
                                                    type
                                                }
                                                value={type}
                                            >
                                                {type}
                                            </option>
                                        );
                                    })}
                            </select>
                        </div>
                    </>
                )}
                {node &&
                    [
                        "App\\Http\\Controllers\\Api\\DataBusController::oneRecord",
                        "App\\Http\\Controllers\\Api\\DataBusController::manyRecords",
                    ].includes(route_function_value?.split("_")[0]) && (
                        <JoinTablesForm
                            mainColumns={columns ?? []}
                            node={node}
                            database={
                                node?.properties?.value?.node_database ??
                                selected_database
                            }
                            mainTables={tables}
                            MainTable={selected_table}
                        ></JoinTablesForm>
                    )}
            </div>
        )
    );
}

// Render the component to the DOM
if (document.getElementById("data_bus_fields"))
    ReactDOM.render(<App />, document.getElementById("data_bus_fields"));
