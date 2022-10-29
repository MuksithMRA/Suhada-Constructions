export class Finance{
    id = 0;
    expense_name = '';
    expense_type = '';
    amount = 0;
    doc = '';
    created = '';

    constructor(id, expense_name, expense_type, amount, doc, created){
        this.id = id;
        this.expense_name = expense_name;
        this.expense_type = expense_type;
        this.amount = amount;
        this.doc = doc;
        this.created = created;
    }

    static toJson(finance){
        return {
            id: finance.id,
            expense_name: finance.expense_name,
            expense_type: finance.expense_type,
            amount: finance.amount,
            doc: finance.doc,
            created: finance.created
        }
    }

    static fromJSON(json){
        return new Finance(json.id, json.expense_name, json.expense_type, json.amount, json.doc, json.created);
    }
}