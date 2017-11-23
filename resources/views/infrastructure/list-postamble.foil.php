
<br /><br />
<div class="well">
    <h3>Infrastructures</h3>

    <p>
        Generally, an <em>infrastructure</em> represents a collection of switches which form an IXP's peering LAN.
    </p>

    <p>
        For example, INEX runs three infrastructures - <em>INEX LAN1</em>, <em>INEX LAN2</em> and <em>INEX Cork</em>.
        Each of these consist of a unique set of switches and these infrastructures are not interconnected. A fibre /
        switch / PSU / etc. failure on INEX LAN1 should have absolutely no effect on INEX LAN2 or INEX Cork. Each
        infrastructure has its own set of switches and its own VLAN(s) (production VLAN, quarantine VLAN, etc).
    </p>

    <p>
        Another way to think of an infrastructure is to consider two infrastructures as two different IXPs. In fact INEX
        had unique PeeringDB entries (INEX
            <a href="https://www.peeringdb.com/api/ix/48">LAN1</a>,
            <a href="https://www.peeringdb.com/api/ix/387">LAN2</a>,
            <a href="https://www.peeringdb.com/api/ix/1262">Cork</a>
        ) and IX-F entries (INEX
            <a href="https://db.ix-f.net/api/ixp/20">LAN1</a>,
            <a href="https://db.ix-f.net/api/ixp/645">LAN2</a>,
            <a href="https://db.ix-f.net/api/ixp/646">Cork</a>
        ) for each infrastructure.
    </p>

    <hr>
    There is a database object representing your IXP. You can edit it
    <a href="<?= route( 'ixp@edit', [ 'id' => 1 ] ) ?>">by clicking here</a>.
</div>
