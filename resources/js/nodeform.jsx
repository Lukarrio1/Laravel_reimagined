import { MultiSelect } from "react-multi-select-component";

function App() {
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
    const [node_item, setNodeItem] = React.useState(null);
    const [route_function_value, setRouteFunctionValue] = React.useState(null);
    const [orderByTypes, setOrderByTypes] = React.useState(null);
    const [selected_order_by_type, setSelectedOrderByTypes] =
        React.useState(null);
    const [data_limit, setDataLimit] = React.useState(null);

    function createQueryString(params) {
        const queryString = Object.keys(params)
            .map(
                (key) =>
                    encodeURIComponent(key) +
                    "=" +
                    encodeURIComponent(params[key])
            )
            .join("&");
        return queryString;
    }
    const getData = async () => {
        const { data } = await axios.get(
            "/node/databus?" +
                createQueryString({
                    database: selected_database,
                    table: selected_table,
                    node_id: node_id?.value,
                    node_table_columns: selected_columns,
                    node_has_many: node_many_data,
                })
        );

        setDatabases(data?.databases);
        setTables(data?.tables);
        setTableColumns(data?.columns);
        setNode(data?.node);
        setOrderByTypes(data?.orderByTypes);
        setTableItems(data?.table_items);
    };

    React.useEffect(() => {
        node_type.addEventListener("change", (e) => {
            setLaunch(+e.target.value == 1);
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
                    ].includes(e.target.value.split("_")[0]) == true
                );
            });
    }, [launch]);

    React.useEffect(() => {
        getData();
    }, [selected_database, selected_table, selected_columns]);

    React.useEffect(() => {
        if (!node) return;
        setSelectedDatabases(node?.properties?.value?.node_database);
        setSelectedTable(node?.properties?.value?.node_table);
        setRouteFunctionValue(route_function?.value);
        setDataLimit(node?.properties?.value?.node_data_limit);
        setNodeDisplayAid(node?.properties?.value?.node_item_display_aid);
        setLaunch(false);
        setLaunch(true);
    }, [node]);

    React.useEffect(() => {
        if (!node_route_label) return;
        if (
            route_function_value ==
            "App\\Http\\Controllers\\Api\\DataBusController::oneRecord"
        )
            node_route_label.innerHTML =
                "Node route (you can add parameters to the route eg. test/{param}/{param1} which will then filter the data)";
        else
            node_route_label.innerHTML =
                "Node route (you can add parameters to the route eg. test/{param}/{param1} which will then filter the data)";
        console.log(route_function_value, "route_function_value");
    }, [route_function_value]);

    return (
        launch && (
            <div>
                <div class="mb-3">
                    <label for="node_database" class="form-label">
                        Node Database
                    </label>
                    <select
                        id="node_database"
                        class="form-select"
                        name="node_database"
                        onChange={(e) => setSelectedDatabases(e.target.value)}
                    >
                        <option>Select A Database</option>
                        {databases &&
                            databases.map((database) => {
                                return (
                                    <option
                                        selected={
                                            node?.properties?.value
                                                ?.node_database == database
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
                        onChange={(e) => setSelectedTable(e.target.value)}
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
                {"App\\Http\\Controllers\\Api\\DataBusController::deleteRecord" !=
                    route_function_value?.split("_")[0] && (
                    <div class="mb-3">
                        <label for="node_table" class="form-label">
                            Node Table Columns
                        </label>
                        <select
                            id="node_table_columns"
                            class="form-select"
                            name="node_table_columns[]"
                            onChange={(e) =>
                                setSelectedTableColumns(e.target.value)
                            }
                            multiple
                            disabled={[
                                "App\\Http\\Controllers\\Api\\DataBusController::deleteRecord",
                                "App\\Http\\Controllers\\Api\\DataBusController::checkRecord",
                            ]?.includes(route_function_value?.split("_")[0])}
                        >
                            <option value="">Select A Table Columns</option>
                            {columns &&
                                columns.map((column) => {
                                    return (
                                        <option
                                            selected={node?.properties?.value?.node_table_columns?.includes(
                                                column
                                            )}
                                            value={column}
                                        >
                                            {column}
                                        </option>
                                    );
                                })}
                        </select>
                    </div>
                )}
                {"App\\Http\\Controllers\\Api\\DataBusController::oneRecord" ==
                    route_function_value?.split("_")[0] && (
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
                                {columns &&
                                    columns.map((column) => {
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
                        <div class="mb-3">
                            <label for="node_table" class="form-label">
                                Node Item
                            </label>
                            <select
                                id="node_item"
                                class="form-select"
                                name="node_item"
                                onChange={(e) => setNodeItem(e.target.value)}
                            >
                                <option value="">Select node item</option>
                                {display_aid &&
                                    table_items &&
                                    table_items.map((item) => {
                                        return (
                                            <option
                                                selected={
                                                    node?.properties?.value
                                                        ?.node_item == item?.id
                                                }
                                                value={item.id}
                                            >
                                                {item[display_aid]}
                                            </option>
                                        );
                                    })}
                            </select>
                        </div>
                    </>
                )}
                {"App\\Http\\Controllers\\Api\\DataBusController::manyRecords" ==
                    route_function_value && (
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
            </div>
        )
    );
}

// Render the component to the DOM
ReactDOM.render(<App />, document.getElementById("data_bus_fields"));
