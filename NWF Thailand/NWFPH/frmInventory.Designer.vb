<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frminventory
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.dtgInvertory = New System.Windows.Forms.DataGridView()
        Me.chkbtn = New System.Windows.Forms.DataGridViewCheckBoxColumn()
        Me.ITEMKEY = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.LOTNO = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.soh = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.qty_commited = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.qty_available = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.expiry = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.Panel1 = New System.Windows.Forms.Panel()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnSearch = New System.Windows.Forms.Button()
        Me.txtSearch = New System.Windows.Forms.TextBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.txtFilter = New System.Windows.Forms.ComboBox()
        Me.btnViewAll = New System.Windows.Forms.Button()
        Me.Inventory = New System.Windows.Forms.TabControl()
        Me.TabPage1 = New System.Windows.Forms.TabPage()
        Me.btnTransfer = New System.Windows.Forms.Button()
        Me.TabPage2 = New System.Windows.Forms.TabPage()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.btninvsrch = New System.Windows.Forms.Button()
        Me.invtxtfilter = New System.Windows.Forms.TextBox()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.invfilter = New System.Windows.Forms.ComboBox()
        Me.btninvview = New System.Windows.Forms.Button()
        Me.Panel2 = New System.Windows.Forms.Panel()
        Me.dgvinv = New System.Windows.Forms.DataGridView()
        Me.chkbox = New System.Windows.Forms.DataGridViewCheckBoxColumn()
        Me.DataGridViewTextBoxColumn1 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn2 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn3 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn4 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn5 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.DataGridViewTextBoxColumn6 = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.dtgInvertory, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.Panel1.SuspendLayout()
        Me.GroupBox1.SuspendLayout()
        Me.Inventory.SuspendLayout()
        Me.TabPage1.SuspendLayout()
        Me.TabPage2.SuspendLayout()
        Me.GroupBox2.SuspendLayout()
        Me.Panel2.SuspendLayout()
        CType(Me.dgvinv, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'dtgInvertory
        '
        Me.dtgInvertory.AllowUserToAddRows = False
        Me.dtgInvertory.AllowUserToDeleteRows = False
        Me.dtgInvertory.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dtgInvertory.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.chkbtn, Me.ITEMKEY, Me.LOTNO, Me.soh, Me.qty_commited, Me.qty_available, Me.expiry})
        Me.dtgInvertory.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dtgInvertory.Location = New System.Drawing.Point(0, 0)
        Me.dtgInvertory.Name = "dtgInvertory"
        Me.dtgInvertory.RowHeadersWidth = 51
        Me.dtgInvertory.Size = New System.Drawing.Size(1422, 499)
        Me.dtgInvertory.TabIndex = 0
        '
        'chkbtn
        '
        Me.chkbtn.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.AllCells
        Me.chkbtn.FalseValue = "0"
        Me.chkbtn.HeaderText = ""
        Me.chkbtn.MinimumWidth = 6
        Me.chkbtn.Name = "chkbtn"
        Me.chkbtn.TrueValue = "1"
        Me.chkbtn.Width = 6
        '
        'ITEMKEY
        '
        Me.ITEMKEY.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.ITEMKEY.HeaderText = "ITEMKEY"
        Me.ITEMKEY.MinimumWidth = 6
        Me.ITEMKEY.Name = "ITEMKEY"
        '
        'LOTNO
        '
        Me.LOTNO.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.LOTNO.HeaderText = "LOTNO"
        Me.LOTNO.MinimumWidth = 6
        Me.LOTNO.Name = "LOTNO"
        '
        'soh
        '
        Me.soh.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.soh.HeaderText = "SOH"
        Me.soh.MinimumWidth = 6
        Me.soh.Name = "soh"
        '
        'qty_commited
        '
        Me.qty_commited.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.qty_commited.HeaderText = "QTY COMMITTED"
        Me.qty_commited.MinimumWidth = 6
        Me.qty_commited.Name = "qty_commited"
        '
        'qty_available
        '
        Me.qty_available.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.qty_available.HeaderText = "QTY AVAILABLE"
        Me.qty_available.MinimumWidth = 6
        Me.qty_available.Name = "qty_available"
        '
        'expiry
        '
        Me.expiry.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.expiry.HeaderText = "EXPIRY"
        Me.expiry.MinimumWidth = 6
        Me.expiry.Name = "expiry"
        '
        'Panel1
        '
        Me.Panel1.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Panel1.AutoSize = True
        Me.Panel1.Controls.Add(Me.dtgInvertory)
        Me.Panel1.Location = New System.Drawing.Point(6, 73)
        Me.Panel1.Name = "Panel1"
        Me.Panel1.Size = New System.Drawing.Size(1422, 499)
        Me.Panel1.TabIndex = 1
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.btnSearch)
        Me.GroupBox1.Controls.Add(Me.txtSearch)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.txtFilter)
        Me.GroupBox1.Controls.Add(Me.btnViewAll)
        Me.GroupBox1.Location = New System.Drawing.Point(6, -6)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(1422, 73)
        Me.GroupBox1.TabIndex = 2
        Me.GroupBox1.TabStop = False
        '
        'btnSearch
        '
        Me.btnSearch.Location = New System.Drawing.Point(1054, 24)
        Me.btnSearch.Name = "btnSearch"
        Me.btnSearch.Size = New System.Drawing.Size(104, 38)
        Me.btnSearch.TabIndex = 4
        Me.btnSearch.Text = "Search"
        Me.btnSearch.UseVisualStyleBackColor = True
        '
        'txtSearch
        '
        Me.txtSearch.Location = New System.Drawing.Point(346, 29)
        Me.txtSearch.Name = "txtSearch"
        Me.txtSearch.Size = New System.Drawing.Size(693, 31)
        Me.txtSearch.TabIndex = 3
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.ForeColor = System.Drawing.SystemColors.Control
        Me.Label1.Location = New System.Drawing.Point(6, 31)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(66, 25)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "Filter"
        '
        'txtFilter
        '
        Me.txtFilter.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.txtFilter.FormattingEnabled = True
        Me.txtFilter.Items.AddRange(New Object() {"ITEM KEY", "LOT NO", "BARCODE", "MAX QTY"})
        Me.txtFilter.Location = New System.Drawing.Point(78, 27)
        Me.txtFilter.Name = "txtFilter"
        Me.txtFilter.Size = New System.Drawing.Size(262, 33)
        Me.txtFilter.TabIndex = 1
        '
        'btnViewAll
        '
        Me.btnViewAll.Location = New System.Drawing.Point(1164, 24)
        Me.btnViewAll.Name = "btnViewAll"
        Me.btnViewAll.Size = New System.Drawing.Size(104, 38)
        Me.btnViewAll.TabIndex = 0
        Me.btnViewAll.Text = "View All"
        Me.btnViewAll.UseVisualStyleBackColor = True
        '
        'Inventory
        '
        Me.Inventory.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Inventory.Controls.Add(Me.TabPage1)
        Me.Inventory.Controls.Add(Me.TabPage2)
        Me.Inventory.Location = New System.Drawing.Point(1, 1)
        Me.Inventory.Name = "Inventory"
        Me.Inventory.SelectedIndex = 0
        Me.Inventory.Size = New System.Drawing.Size(1442, 662)
        Me.Inventory.TabIndex = 3
        '
        'TabPage1
        '
        Me.TabPage1.BackColor = System.Drawing.Color.FromArgb(CType(CType(112, Byte), Integer), CType(CType(119, Byte), Integer), CType(CType(147, Byte), Integer))
        Me.TabPage1.Controls.Add(Me.btnTransfer)
        Me.TabPage1.Controls.Add(Me.GroupBox1)
        Me.TabPage1.Controls.Add(Me.Panel1)
        Me.TabPage1.Font = New System.Drawing.Font("Microsoft Sans Serif", 15.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.TabPage1.Location = New System.Drawing.Point(4, 33)
        Me.TabPage1.Name = "TabPage1"
        Me.TabPage1.Padding = New System.Windows.Forms.Padding(3)
        Me.TabPage1.Size = New System.Drawing.Size(1434, 625)
        Me.TabPage1.TabIndex = 0
        Me.TabPage1.Text = "Inventory List"
        '
        'btnTransfer
        '
        Me.btnTransfer.Anchor = CType((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left), System.Windows.Forms.AnchorStyles)
        Me.btnTransfer.Location = New System.Drawing.Point(7, 578)
        Me.btnTransfer.Name = "btnTransfer"
        Me.btnTransfer.Size = New System.Drawing.Size(306, 38)
        Me.btnTransfer.TabIndex = 3
        Me.btnTransfer.Text = "Transfer to Adjustment"
        Me.btnTransfer.UseVisualStyleBackColor = True
        '
        'TabPage2
        '
        Me.TabPage2.BackColor = System.Drawing.Color.FromArgb(CType(CType(112, Byte), Integer), CType(CType(119, Byte), Integer), CType(CType(147, Byte), Integer))
        Me.TabPage2.Controls.Add(Me.GroupBox2)
        Me.TabPage2.Controls.Add(Me.Panel2)
        Me.TabPage2.Location = New System.Drawing.Point(4, 33)
        Me.TabPage2.Name = "TabPage2"
        Me.TabPage2.Padding = New System.Windows.Forms.Padding(3)
        Me.TabPage2.Size = New System.Drawing.Size(1434, 625)
        Me.TabPage2.TabIndex = 1
        Me.TabPage2.Text = "Inventory Adjustment"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.btninvsrch)
        Me.GroupBox2.Controls.Add(Me.invtxtfilter)
        Me.GroupBox2.Controls.Add(Me.Label2)
        Me.GroupBox2.Controls.Add(Me.invfilter)
        Me.GroupBox2.Controls.Add(Me.btninvview)
        Me.GroupBox2.Location = New System.Drawing.Point(7, 0)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(1422, 73)
        Me.GroupBox2.TabIndex = 3
        Me.GroupBox2.TabStop = False
        '
        'btninvsrch
        '
        Me.btninvsrch.Location = New System.Drawing.Point(863, 24)
        Me.btninvsrch.Name = "btninvsrch"
        Me.btninvsrch.Size = New System.Drawing.Size(104, 38)
        Me.btninvsrch.TabIndex = 4
        Me.btninvsrch.Text = "Search"
        Me.btninvsrch.UseVisualStyleBackColor = True
        '
        'invtxtfilter
        '
        Me.invtxtfilter.Location = New System.Drawing.Point(346, 29)
        Me.invtxtfilter.Name = "invtxtfilter"
        Me.invtxtfilter.Size = New System.Drawing.Size(505, 29)
        Me.invtxtfilter.TabIndex = 3
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.ForeColor = System.Drawing.SystemColors.Control
        Me.Label2.Location = New System.Drawing.Point(6, 31)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(57, 24)
        Me.Label2.TabIndex = 2
        Me.Label2.Text = "Filter"
        '
        'invfilter
        '
        Me.invfilter.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.invfilter.FormattingEnabled = True
        Me.invfilter.Items.AddRange(New Object() {"ITEM KEY", "LOT NO", "BARCODE"})
        Me.invfilter.Location = New System.Drawing.Point(78, 27)
        Me.invfilter.Name = "invfilter"
        Me.invfilter.Size = New System.Drawing.Size(262, 32)
        Me.invfilter.TabIndex = 1
        '
        'btninvview
        '
        Me.btninvview.Location = New System.Drawing.Point(973, 24)
        Me.btninvview.Name = "btninvview"
        Me.btninvview.Size = New System.Drawing.Size(104, 38)
        Me.btninvview.TabIndex = 0
        Me.btninvview.Text = "View All"
        Me.btninvview.UseVisualStyleBackColor = True
        '
        'Panel2
        '
        Me.Panel2.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Panel2.AutoSize = True
        Me.Panel2.Controls.Add(Me.dgvinv)
        Me.Panel2.Location = New System.Drawing.Point(6, 83)
        Me.Panel2.Name = "Panel2"
        Me.Panel2.Size = New System.Drawing.Size(1425, 526)
        Me.Panel2.TabIndex = 2
        '
        'dgvinv
        '
        Me.dgvinv.AllowUserToAddRows = False
        Me.dgvinv.AllowUserToDeleteRows = False
        Me.dgvinv.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.dgvinv.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.chkbox, Me.DataGridViewTextBoxColumn1, Me.DataGridViewTextBoxColumn2, Me.DataGridViewTextBoxColumn3, Me.DataGridViewTextBoxColumn4, Me.DataGridViewTextBoxColumn5, Me.DataGridViewTextBoxColumn6})
        Me.dgvinv.Dock = System.Windows.Forms.DockStyle.Fill
        Me.dgvinv.Location = New System.Drawing.Point(0, 0)
        Me.dgvinv.Name = "dgvinv"
        Me.dgvinv.ReadOnly = True
        Me.dgvinv.RowHeadersWidth = 51
        Me.dgvinv.Size = New System.Drawing.Size(1425, 526)
        Me.dgvinv.TabIndex = 0
        '
        'chkbox
        '
        Me.chkbox.HeaderText = ""
        Me.chkbox.MinimumWidth = 6
        Me.chkbox.Name = "chkbox"
        Me.chkbox.ReadOnly = True
        Me.chkbox.TrueValue = "Y"
        Me.chkbox.Visible = False
        Me.chkbox.Width = 125
        '
        'DataGridViewTextBoxColumn1
        '
        Me.DataGridViewTextBoxColumn1.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn1.HeaderText = "ITEMKEY"
        Me.DataGridViewTextBoxColumn1.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn1.Name = "DataGridViewTextBoxColumn1"
        Me.DataGridViewTextBoxColumn1.ReadOnly = True
        '
        'DataGridViewTextBoxColumn2
        '
        Me.DataGridViewTextBoxColumn2.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn2.HeaderText = "LOTNO"
        Me.DataGridViewTextBoxColumn2.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn2.Name = "DataGridViewTextBoxColumn2"
        Me.DataGridViewTextBoxColumn2.ReadOnly = True
        '
        'DataGridViewTextBoxColumn3
        '
        Me.DataGridViewTextBoxColumn3.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn3.HeaderText = "SOH"
        Me.DataGridViewTextBoxColumn3.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn3.Name = "DataGridViewTextBoxColumn3"
        Me.DataGridViewTextBoxColumn3.ReadOnly = True
        '
        'DataGridViewTextBoxColumn4
        '
        Me.DataGridViewTextBoxColumn4.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn4.HeaderText = "QTY COMMITTED"
        Me.DataGridViewTextBoxColumn4.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn4.Name = "DataGridViewTextBoxColumn4"
        Me.DataGridViewTextBoxColumn4.ReadOnly = True
        '
        'DataGridViewTextBoxColumn5
        '
        Me.DataGridViewTextBoxColumn5.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn5.HeaderText = "QTY AVAILABLE"
        Me.DataGridViewTextBoxColumn5.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn5.Name = "DataGridViewTextBoxColumn5"
        Me.DataGridViewTextBoxColumn5.ReadOnly = True
        '
        'DataGridViewTextBoxColumn6
        '
        Me.DataGridViewTextBoxColumn6.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.DataGridViewTextBoxColumn6.HeaderText = "EXPIRY"
        Me.DataGridViewTextBoxColumn6.MinimumWidth = 6
        Me.DataGridViewTextBoxColumn6.Name = "DataGridViewTextBoxColumn6"
        Me.DataGridViewTextBoxColumn6.ReadOnly = True
        '
        'frminventory
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(96.0!, 96.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi
        Me.BackColor = System.Drawing.Color.FromArgb(CType(CType(112, Byte), Integer), CType(CType(119, Byte), Integer), CType(CType(147, Byte), Integer))
        Me.ClientSize = New System.Drawing.Size(1455, 667)
        Me.Controls.Add(Me.Inventory)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None
        Me.Name = "frminventory"
        Me.Text = "frminventory"
        CType(Me.dtgInvertory, System.ComponentModel.ISupportInitialize).EndInit()
        Me.Panel1.ResumeLayout(False)
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        Me.Inventory.ResumeLayout(False)
        Me.TabPage1.ResumeLayout(False)
        Me.TabPage1.PerformLayout()
        Me.TabPage2.ResumeLayout(False)
        Me.TabPage2.PerformLayout()
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox2.PerformLayout()
        Me.Panel2.ResumeLayout(False)
        CType(Me.dgvinv, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents dtgInvertory As DataGridView
    Friend WithEvents Panel1 As Panel
    Friend WithEvents GroupBox1 As GroupBox
    Friend WithEvents Label1 As Label
    Friend WithEvents txtFilter As ComboBox
    Friend WithEvents btnViewAll As Button
    Friend WithEvents btnSearch As Button
    Friend WithEvents txtSearch As TextBox
    Friend WithEvents Inventory As TabControl
    Friend WithEvents TabPage1 As TabPage
    Friend WithEvents TabPage2 As TabPage
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents btninvsrch As Button
    Friend WithEvents invtxtfilter As TextBox
    Friend WithEvents Label2 As Label
    Friend WithEvents invfilter As ComboBox
    Friend WithEvents btninvview As Button
    Friend WithEvents Panel2 As Panel
    Friend WithEvents dgvinv As DataGridView
    Friend WithEvents chkbtn As DataGridViewCheckBoxColumn
    Friend WithEvents ITEMKEY As DataGridViewTextBoxColumn
    Friend WithEvents LOTNO As DataGridViewTextBoxColumn
    Friend WithEvents soh As DataGridViewTextBoxColumn
    Friend WithEvents qty_commited As DataGridViewTextBoxColumn
    Friend WithEvents qty_available As DataGridViewTextBoxColumn
    Friend WithEvents expiry As DataGridViewTextBoxColumn
    Friend WithEvents btnTransfer As Button
    Friend WithEvents chkbox As DataGridViewCheckBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn1 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn2 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn3 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn4 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn5 As DataGridViewTextBoxColumn
    Friend WithEvents DataGridViewTextBoxColumn6 As DataGridViewTextBoxColumn
End Class
